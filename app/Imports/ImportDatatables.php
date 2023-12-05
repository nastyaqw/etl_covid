<?php

namespace App\Imports;

use App\Models\Datatable;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ImportDatatables implements ToModel, WithStartRow, WithCustomCsvSettings
{
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
        return new Datatable([
                'start_date' => Carbon::parse($row[0])->format('Y-m-d'),  
                'end_date' => Carbon::parse('12.12.2012')->format('Y-m-d'),
                'region' => $row[1],
                'hospitalized'=> NULL,
                'infected'  => $row[6],
                'recovered' => $row[7] ,
                'deaths' => $row[5],
            
        ]);
    }
}
