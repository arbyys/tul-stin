<?php

namespace App\Services;

use App\Models\Currency;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use League\Csv\Reader;
use GuzzleHttp\Client;
use Carbon\Carbon;

class CurrencyService
{
    public static function updateExchangeRates($bypassDates=false)
    {
        $currencyCZK = Currency::find("CZK");
        if (!$currencyCZK)
        {
            $currencyCZK = new Currency();
            $currencyCZK->fill([
                "country" => "Česká republika",
                "name" => "koruna",
                "code" => "CZK",
            ]);
        }

        if(is_null($currencyCZK->updated_at)) {
            $bypassDates = true;
        }

        if(!$bypassDates) {
            $date1 = Carbon::parse($currencyCZK->updated_at);
            $currentDateTime = Carbon::now();

            $isOlderThanCached = $currentDateTime->diffInDays($date1) >= 1;
            $isWeekday = $currentDateTime->isWeekday();
            $isAfterReleaseDate = $currentDateTime->hour > 14 || ($currentDateTime->hour == 14 && $currentDateTime->minute >= 31);
        }

        if ($bypassDates || ($isOlderThanCached && $isWeekday && $isAfterReleaseDate)) {
            $apiUrl = env('API_URL');

            $response = Http::get($apiUrl);

            $csvContent = $response->body();

            $reader = Reader::createFromString($csvContent);

            $currentDateRow = $reader->fetchOne();

            $reader->setDelimiter("|");
            $reader->setHeaderOffset(1);

            $currentDate = explode(" ", $currentDateRow[0])[0];

            $date2 = Carbon::createFromFormat('d.m.Y', $currentDate);

            if ($bypassDates || $date1->diffInDays($date2) >= 1) {
                $currencyCZK->save();
                foreach ($reader as $index=>$row) {
                    if($index <= 1)
                    {
                        continue;
                    }
                    $currency = Currency::find($row['kód']);
                    if (!$currency)
                    {
                        $currency = new Currency();
                    }

                    $rateReplaced = floatval(str_replace(',', '.', $row['kurz']));
                    $rateCalculated = $rateReplaced / intval($row['množství']);
                    $currency->fill([
                        'country' => $row['země'],
                        'name' => $row['měna'],
                        'code' => $row['kód'],
                        'rate' => $rateCalculated
                    ]);
                    $currency->save();
                }
            }
        }
    }
}
