<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * The product model instance.
     *
     * @var \App\Models\Product
     */
    protected $product;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->product = new Product();
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

        return $this->product->getPaginatedData(
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
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'outlet_id' => 'nullable|integer|exists:outlets,id'
        ]);

        $this->product->saveData($request->all());

        return $this->product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'outlet_id' => 'nullable|integer|exists:outlets,id'
        ]);

        $product->saveData($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return null;
    }
}
