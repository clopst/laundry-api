<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Get all of the owners for the outlet.
     *
     * @return \lluminate\Database\Eloquent\Relations\MorphToMany
     */
    public function owners()
    {
        return $this->morphToMany(User::class, 'userable')->where('role', 'owner');
    }

    /**
     * Get all of the cashiers for the outlet.
     *
     * @return \lluminate\Database\Eloquent\Relations\MorphToMany
     */
    public function cashiers()
    {
        return $this->morphToMany(User::class, 'userable')->where('role', 'cashier');
    }
}
