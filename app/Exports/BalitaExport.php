<?php

namespace App\Exports;

use App\Models\MasterBalita;
use Maatwebsite\Excel\Concerns\FromCollection;

class BalitaExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return MasterBalita::all();
    }
}
