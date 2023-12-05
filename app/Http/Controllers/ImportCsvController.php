<?php

namespace App\Http\Controllers;
use App\Models\Datatable;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportDatatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
set_time_limit(3600);

class ImportCsvController extends Controller
{
    public function importCSV(Request $request)
    { 
        //dd(request()->file('csv_file'));
        try{
            $path = $request->file('csv_file')->getRealPath();
            $csvData = Excel::toArray('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];

            foreach ($csvData as $row) {
                $rows = implode(";", $row);
                $rows = explode(";", $rows);
                $newCsvData[] = $rows;
            }
        $weeklyData = [];
    
        for ($i = 1, $len = count($newCsvData); $i < $len; $i += 1) {
            $data = $newCsvData[$i];
            
            $region = $data[1];
            $date = $data[0];
            $infected = $data[6];
            $deaths = $data[5];
            $recovered = $data[7];
        
            $startOfWeek = Carbon::parse($date)->startOfWeek();
            $endOfWeek = Carbon::parse($date)->endOfWeek();
            //Создает уникальный ключ для каждого регионы из недели
            $key = $region . '_' . $startOfWeek->toDateString('Y-m-d') . '_' . $endOfWeek->toDateString('Y-m-d');
            //Если ключа не существует, то добавляем его
            if (!isset($weeklyData[$key])) {
                $weeklyData[$key] = [
                    'region' => $region,
                    'start_date' => $startOfWeek,
                    'end_date' => $endOfWeek,
                    'infected' => 0,
                    'deaths' => 0,
                    'recovered' => 0,
                    'hospitalized' => 0,
                ];
            }
            
            $weeklyData[$key]['infected'] += $infected;
            $weeklyData[$key]['deaths'] += $deaths;
            $weeklyData[$key]['recovered'] += $recovered;
            $hospitalized = $infected * 0.1;
            $weeklyData[$key]['hospitalized'] += $hospitalized;
        }
    //dd($weeklyData);
        //Добавление в БД
        foreach ($weeklyData as $data) {
            Datatable::create([
                'start_date' => $data['start_date']->toDateString('Y-m-d'),
                'end_date' => $data['end_date']->toDateString('Y-m-d'),
                'region' => $data['region'],
                'hospitalized' => $data['hospitalized'],
                'infected' => $data['infected'],
                'recovered' => $data['recovered'],
                'deaths' => $data['deaths'],
            ]);
        }

        return redirect()->back()->with('success', 'CSV файл успешно импортирован');
        Log::info('CSV импортирован в базу данных');

        //Обработка ошибок
        }catch (\Illuminate\Database\QueryException $e){
            return redirect()->back()->with('error', 'Ошибка загрузки CSV файла');
            Log::info('Ошибка добавления CSV файла в базу данных');
        }
        
}
public function src()
{
    return view('import');
}

    }

