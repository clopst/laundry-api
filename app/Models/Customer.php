<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory, WithPaginatedData, ModelOperation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address'
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'email',
        'phone_number',
        'address'
    ];

    /**
     * Get customers count.
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count();
    }
}
