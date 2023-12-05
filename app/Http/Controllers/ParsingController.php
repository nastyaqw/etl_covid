<?php

namespace App\Http\Controllers;

use App\Models\Datatable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class ParsingController extends Controller
{
    public function parseData() 
    {
        $date_format = 'd.m.Y';
        $client = new Client();
$website = 'https://xn--90aivcdt6dxbc.xn--p1ai';
/*
$urls = ['https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-3-655-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-4-509-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-7-280-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-10-327-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-13-513-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-16-297-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-15-872-cheloveka/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-15-722-cheloveka/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-20-754-cheloveka/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-22-993-cheloveka/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-29-987-chelovek/',
'https://xn--90aivcdt6dxbc.xn--p1ai/stopkoronavirus/v-rossii-za-nedelyu-vyzdorovelo-39-731-chelovek/'
];
foreach ($urls as $url) {
    */
        $response = $client->request('GET', $website.'/stopkoronavirus/');

        $crawler = new Crawler((string) $response->getBody());
      
        $mainText = $crawler->filter('a')->each(function (Crawler $node) {
            return $node->attr('href');
        });
       // dd($mainText);
       foreach($mainText as $main){
        if(strpos($main, 'v-rossii-za-nedelyu-vyzdorovelo-') !== false){
            //array_push($urls, $main);
            $url = $website.$main;
            break;
           }
        };

$response = $client->request('GET', $url);
$crawler = new Crawler((string) $response->getBody());

$detailBody = $crawler->filter('div.article-detail__body');

$tableData = $detailBody->filter('tbody td')->each(function ($node) {
    return $node->text();
});
//dd($tableData);
$data = $detailBody->filter('h3')->text();

        preg_match_all('/\d+.?\d*.? - \d+.?\d*|\d+.?\d*- \d+.?\d*/', $data, $matches);

        if (empty($matches[0])) {
            return;
        }

        $dates = explode('-', $matches[0][0]);
        
        $dates[0] = trim($dates[0], ' .');
        $dates[1] = trim($dates[1], ' .');
       //dd($dates);

        //dd(explode('.', $dates[0])[1]);
        try {
            $current_year = Carbon::now()->year;

           if ((int)explode('.', $dates[0])[1] > (int)explode('.', $dates[1])[1]) {
                $date_from = Carbon::createFromFormat($date_format, trim($dates[0]).'.'.($current_year - 1));
           } else {
                $date_from = Carbon::createFromFormat($date_format, trim($dates[0]).'.'.$current_year);
            }

            $date_to = Carbon::createFromFormat($date_format, trim($dates[1]).'.'.$current_year);
           
        } catch (\Exception $e) {
            echo 'Invalid date format: '.json_encode($dates);
            return;
        }

       // return compact('date_from', 'date_to');
       foreach ($tableData as $i => $td) {
        if(trim($tableData[$i], ' .') == 'Российская Федерация'){
            break;
        }
       }
       $tableData = array_slice($tableData, $i);

       foreach ($tableData as $i => $td) {
           $tableData[$i] = trim(preg_replace('/\n\t\r/', '', $td));

           $temp = str_replace(' ', '', $tableData[$i]);
           if (ctype_digit($temp)) {
               $tableData[$i] = (int)$temp;
           }
       }
       //dd($tableData);
$regionsData = [];
        $fields = ['start_date', 'end_date', 'region', 'hospitalized', 'recovered', 'infected', 'deaths'];

$dates = ['start_date'=>$date_from,'end_date'=>$date_to];
        for ($i = 0, $len = count($tableData); $i < $len; $i += 5) {
            $slice = array_slice($tableData, $i, 5);
            $keys  = array_slice($fields, 2, count($slice));
            $regionsData[] = array_merge($dates, array_combine($keys, $slice));
        }
//return $regionsData;
Log::info('Обработанные данные: ', $regionsData); 
foreach($regionsData as $record){
    try{ 

        // Логирование данных записи перед их обработкой
        Log::info('Обработка записи: ', $record);
        //echo $record['region']."<br/>";
        Datatable::create($record); 
       
    } catch (Exception $e) {

        // Логирование ошибки
        Log::error("Ошибка обработки записи: " . $e->getMessage()); 
        
        return ['status' => 'error', 'message' => 'Ошибка обработки записи: ' . $e->getMessage()];
    }
}
    Log::info('Записи добавены в бд');
    return ['status' => 'success', 'message' => 'Записи добавлены в бд'];    
}
}