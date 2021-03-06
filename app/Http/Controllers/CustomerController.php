<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * The customer model instance.
     *
     * @var \App\Models\Customer
     */
    protected $customer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customer = new Customer();
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

        return $this->customer->getPaginatedData(
            $request->page,
            $request->perPage,
            $request->sorKey,
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
            'email' => 'required|email|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'avatar_path' => 'nullable|image',
            'role' => 'required|string|in:admin,owner,cashier'
        ]);

        $this->customer->saveData($request->all());

        return $this->customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'nullable|string',
            'customername' => 'nullable|string|unique:customers',
            'email' => 'nullable|email|unique:customers',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar_path' => 'nullable|image',
            'role' => 'nullable|string|in:admin,owner,cashier'
        ]);

        $customer->saveData($request->all());

        return $customer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return null;
    }
}
