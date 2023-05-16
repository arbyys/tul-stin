<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $table = 'currencies';
    public $incrementing = false;
    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'country',
        'name',
        'code',
        'rate'
    ];

    public static function convertToCZK($currency, $amount) {
        $currencyModel = self::where('code', $currency)->first();
        if ($currencyModel && $currency != "CZK")
        {
            return $amount * $currency->rate;
        }

        return null;
    }
}
