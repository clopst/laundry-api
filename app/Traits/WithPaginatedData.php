<?php

namespace App\Traits;

trait WithPaginatedData
{
    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    // protected $searchable = [];

    /**
     * Get paginated data from model.
     *
     * @return mixed
     */
    public function getPaginatedData($page = 1, $perPage = 20, $sortKey = 'id', $sortOrder = 'asc', $search = null, $withRelations = [])
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 20;
        $sortKey = $sortKey ?? 'id';
        $sortOrder = $sortOrder ?? 'asc';
        $search = $search ?? null;

        $query = $this->with($withRelations)->orderBy($sortKey, $sortOrder);

        if ($search) {
            $searchKeyword = "%$search%";
            $query = $query->where(function ($q) use ($searchKeyword) {
                foreach ($this->searchable as $field) {
                    $q->orWhere($field, 'ilike', $searchKeyword);
                }
            });
        }

        return $this->paginate($query, $page, $perPage);
    }

    /**
     * Get paginated query.
     *
     * @return mixed
     */

    public function paginate($query, $page = 1, $perPage = 20)
    {
        $offset = ($page - 1) * $perPage;

        $total = $query->count();
        $lastPage = ceil($total / $perPage);

        $query = $query->offset($offset)->limit($perPage);
        $pagination = [
            'page' => $page,
            'perPage' => $perPage,
            'lastPage' => $lastPage,
            'start' => $page > $lastPage ? 0 : $offset + 1,
            'end' => $page > $lastPage ? 0 : $offset + $query->count(),
            'total' => $total
        ];

        return [
            'results' => $query->get(),
            'pagination' => $pagination
        ];
    }
}
