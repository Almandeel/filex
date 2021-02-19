<?php

namespace App\Imports;

use App\Account;
use App\Supplier;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;


class SupplierImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index=>$row) 
        {
            if($index > 0) {
                $account = Account::newSupplier();
                Supplier::create([
                    'name' => $row[0],
                    'phone' => $row[1],
                    'account_id' => $account->id,
                ]);
            }
        }
    }
}
