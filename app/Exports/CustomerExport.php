<?php

namespace App\Exports;

use App\Customer;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class CustomerExport implements FromView
{
    public function view(): View
    {
        $customers = auth()->user()->customers();
        return view('dashboard.exports.customers', compact('customers'));
    }
}
