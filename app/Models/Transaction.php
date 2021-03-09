<?php

namespace App\Models;

use App\Traits\ModelOperation;
use App\Traits\WithPaginatedData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory, WithPaginatedData, ModelOperation;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'invoice',
        'outlet_id',
        'cashier_id',
        'customer_id',
        'product_id',
        'qty',
        'total_price',
        'status',
        'payment'
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'date',
        'invoice',
        'outlet',
        'cashier',
        'customer',
        'product',
        'qty',
        'total_price',
        'status',
        'payment'
    ];

    /**
     * Get the outlet in the transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the cashier in the transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cashier()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer in the transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the product in the transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get dropdowns data for the purposes of this model.
     *
     * @return array
     */
    public function getDropdowns()
    {
        $data = [];

        $data['customers'] = Customer::all()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id
            ])->all();

        $data['outlets'] = Outlet::all()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id
            ])->all();

        $data['products'] = Product::all()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id,
                'unit' => $item->unit,
                'price' => $item->price
            ])->all();

        $data['cashiers'] = User::has('outlets')
            ->where('role', 'cashier')
            ->get()
            ->map(fn ($item) => [
                'label' => $item->name,
                'value' => $item->id,
                'depends' => $item->outlets[0]->id
            ])->all();

        return $data;
    }

    /**
     * Custom query for sort.
     *
     * @param  array $withRelations
     * @param  string $sortKey
     * @param  string $sortOrder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function querySort($withRelations, $sortKey, $sortOrder)
    {
        if (in_array($sortKey, ['customer', 'outlet', 'product', 'cashier'])) {
            if ($sortKey === 'cashier') {
                $tableName = 'users';
            } else {
                $tableName = Str::plural($sortKey);
            }

            return $this->with($withRelations)
                ->select('transactions.*')
                ->join($tableName . ' as t', 't.id', '=', $sortKey . '_id')
                ->orderBy('t.name', $sortOrder);
        }

        return $this->with($withRelations)->orderBy($sortKey, $sortOrder);
    }

    /**
     * Custom query for sort.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $searchKeyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function querySearch($query, $searchKeyword)
    {
        return $query->where(function ($q) use ($searchKeyword) {
            foreach ($this->searchable as $field) {
                if (in_array($field, ['customer', 'outlet', 'product', 'cashier'])) {
                    $q->orWhereHas($field, function ($q) use ($searchKeyword) {
                        $q->where('name', 'ilike', $searchKeyword);
                    });
                } else {
                    $q->orWhere($field, 'ilike', $searchKeyword);
                }
            }
        });
    }
}
