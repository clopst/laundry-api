<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * The outlet model instance.
     *
     * @var \App\Models\Outlet
     */
    protected $outlet;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->outlet = new Outlet();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'paginate' => 'nullable|in:true,false',
            'page' => 'nullable|integer',
            'perPage' => 'nullable|integer',
            'sortKey' => 'nullable',
            'sortOrder' => 'nullable|in:asc,desc',
            'search' => 'nullable|string',
        ]);

        $this->outlet->append(['owners', 'cashiers']);

        return $this->outlet->getPaginatedData(
            $request->page,
            $request->perPage,
            $request->sortKey,
            $request->sortOrder,
            $request->search
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'owner_ids' => 'nullable|array',
            'cashier_ids' => 'nullable|array'
        ]);

        $this->outlet->saveData($request->all());

        $this->outlet->users()->sync(array_merge($request->owner_ids, $request->cashier_ids));

        return $this->outlet;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function show(Outlet $outlet)
    {
        $outlet->append(['owners', 'cashiers', 'owner_ids', 'cashier_ids']);

        return $outlet;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email|unique:outlets',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'owner_ids' => 'nullable|array',
            'cashier_ids' => 'nullable|array'
        ]);

        $outlet->saveData($request->all());

        $outlet->users()->sync(array_merge($request->owner_ids, $request->cashier_ids));

        return $outlet;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        $outlet->delete();

        return null;
    }

    /**
     * Get dropdowns data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDropdowns(Request $request)
    {
        if ($request->id) {
            $outlet = Outlet::find($request->id);
        } else {
            $outlet = $this->outlet;
        }

        return $outlet->getDropdowns();
    }
}
