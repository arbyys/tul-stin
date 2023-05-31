<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Account extends Model
{
    use HasFactory;
    protected $primaryKey = 'iban';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function booted()
    {
        static::creating(function ($account) {
            $account->iban = Str::random(10);
        });
    }

    protected $fillable = [
        'user_id',
        'currency_code'
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'account_iban', 'iban');
    }

    public function makePayment($amount) {
        $result = self::increment('balance', $amount);

        $payment = new Payment();
        $payment->fill([
            'account_iban' => $this->iban,
            'amount' => $amount,
        ]);
        return $result && $payment->save();
    }

    public function applyInterest() {
        $balance = $this->attributes['balance'];
        $interest = abs($balance) * 0.1;

        return $this->makePayment(-$interest);
    }
}
