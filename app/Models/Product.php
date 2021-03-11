<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory, WithPaginatedData, ModelOperation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'unit',
        'price',
        'description',
        'outlet_id'
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'unit',
        'price',
        'description'
    ];

    /**
     * Get the outlet that owns the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get dropdowns data for the purposes of this model.
     *
     * @return array
     */
    public function getDropdowns()
    {
        $data = [];

        $data['outlets'] = Outlet::all()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id
            ])->all();

        return $data;
    }

    /**
     * Get products count.
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

        if (in_array($user->role, ['owner', 'cashier'])) {
            $query = $query->whereHas('outlet', function ($q) use ($user) {
                $q->whereIn('id', $user->outlets->pluck('id'));
            })->orWhere('outlet_id', null);
        }

        return $query;
    }
}
