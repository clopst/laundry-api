<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Outlet extends Model
{
    use HasFactory, WithPaginatedData, ModelOperation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone_number',
        'address',
        'owner_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'users'
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'phone_number',
        'address',
        'owner_id'
    ];

    /**
     * Get all of the users for the outlet.
     *
     * @return \lluminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphToMany(User::class, 'userable');
    }

    /**
     * Get owners attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOwnersAttribute()
    {
        return $this->users->where('role', 'owner');
    }

    /**
     * Get cashiers attribute.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getCashiersAttribute()
    {
        return $this->users->where('role', 'cashier');
    }

    /**
     * Get owner_ids attribute.
     *
     * @return array
     */
    public function getOwnerIdsAttribute()
    {
        return $this->owners->pluck('id');
    }

    /**
     * Get cashier_ids attribute.
     *
     * @return array
     */
    public function getCashierIdsAttribute()
    {
        return $this->cashiers->pluck('id');
    }

    /**
     * Get dropdowns data for the purposes of this model.
     *
     * @return array
     */
    public function getDropdowns()
    {
        $data = [];

        $data['owners'] = User::where('role', 'owner')->get()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id
            ])->all();

        $data['cashiers'] = User::doesntHave('outlets')
            ->where('role', 'cashier')
            ->orWhereIn('id', $this->cashier_ids)
            ->get()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id
            ])->all();

        return $data;
    }

    /**
     * Get outlets count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count();
    }

    /**
     * Scope a query to filter by logged in user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByAuth($query)
    {
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        Log::debug($user->outlets);

        if (in_array($user->role, ['owner', 'cashier'])) {
            $query = $query->whereIn('id', $user->outlets->pluck('id'));
        }

        return $query;
    }
}
