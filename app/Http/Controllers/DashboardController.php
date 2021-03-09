<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Outlet;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transaction = new Transaction();
        $customer = new Customer();
        $outlet = new Outlet();
        $product = new Product();

        $results = [];

        $results['today_transactions_count'] = $transaction->getTodayCount();
        $results['customers_count'] = $customer->getCount();
        $results['outlets_count'] = $outlet->getCount();
        $results['products_count'] = $product->getCount();
        $results['latest_transactions'] = $transaction->getLatest();

        return $results;
    }
}
