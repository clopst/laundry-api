<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, WithStrictNullComparison
{
    use Exportable;

    public function __construct($startDate = null, $endDate = null, $outletIds = [])
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->outletIds = $outletIds;
    }

    /**
    *  @return \Illuminate\Database\Eloquent\Builder
    */
    public function query()
    {
        $query = Transaction::query()->orderBy('date', 'desc');

        if ($this->startDate) {
            $query->where('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('date', '<=', $this->endDate);
        }

        if ($this->outletIds) {
            $query->whereIn('outlet_id', $this->outletIds);
        }

        return $query;
    }

    public function map($transaction): array
    {
        return [
            $transaction->date,
            $transaction->invoice,
            $transaction->customer->name,
            $transaction->customer->address,
            $transaction->outlet->name,
            $transaction->product->name,
            $transaction->qty . ' ' . $transaction->product->unit,
            $transaction->total_price,
            $transaction->cashier->name,
            $transaction->status,
            $transaction->payment
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Transaksi',
            'Kode Invoice',
            'Customer',
            'Alamat',
            'Outlet',
            'Produk',
            'Qty',
            'Harga',
            'Kasir',
            'Status Transaksi',
            'Status Pembayaran'
        ];
    }
}
