<?php

namespace App\Exports;

use App\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class SupplierExport  implements FromView
{
    public function view(): View
    {
        $suppliers = auth()->user()->suppliers();
        return view('dashboard.exports.suppliers', compact('suppliers'));
    }
}
