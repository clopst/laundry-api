<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
