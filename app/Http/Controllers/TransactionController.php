<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * The transaction model instance.
     *
     * @var \App\Models\Transaction
     */
    protected $transaction;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->transaction = new Transaction();
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

        return $this->transaction->getPaginatedData(
            $request->page,
            $request->perPage,
            $request->sortKey,
            $request->sortOrder,
            $request->search,
            ['customer', 'outlet', 'product', 'cashier']
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
            'outlet_id' => 'nullable|integer|exists:outlets,id',
            'cashier_id' => 'nullable|integer|exists:users,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'qty' => 'nullable|integer',
            'total_price' => 'nullable|integer'
        ]);

        $request->request->add([
            'status' => 'process',
            'payment' => 'pending',
            'date' => date('Y-m-d')
        ]);

        $this->transaction->saveData($request->all());

        return $this->transaction;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $transaction->load(['customer', 'outlet', 'product', 'cashier']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'outlet_id' => 'nullable|integer|exists:outlets,id',
            'cashier_id' => 'nullable|integer|exists:users,id',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'product_id' => 'nullable|integer|exists:products,id',
            'qty' => 'nullable|integer',
            'total_price' => 'nullable|integer',
            'status' => 'nullable|string|in:process,pickup,done',
            'payment' => 'nullable|string|in:pending,done'
        ]);

        $transaction->saveData($request->all());

        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        return $transaction->delete();
    }

    /**
     * Get dropdowns data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDropdowns(Request $request)
    {
        return $this->transaction->getDropdowns();
    }

    /**
     * Get dropdowns data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        $transaction->status = $request->status;
        $transaction->payment = $request->payment;
        $transaction->save();

        return $transaction;
    }

    /**
     * Get exported data as excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $request->validate([
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date',
            'outletIds' => 'nullable|array',
        ]);

        $export = new TransactionsExport(
            $request->startDate,
            $request->endDate,
            $request->outletIds
        );

        return $export->download('transactions_report.xlsx');
    }
}
