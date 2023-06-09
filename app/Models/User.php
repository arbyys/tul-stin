<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google2fa_secret'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Interact with the user's first name.
     *
     * @return Attribute
     */
    protected function google2faSecret(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>  decrypt($value),
            set: fn ($value) =>  encrypt($value),
        );
    }

    public function hasAccountAndEnoughMoney($currencyCode, $amount, $overdraft=false): bool
    {
        if ($overdraft)
        {
            $bal = Account::where("user_id", $this->id)
                    ->where("currency_code", $currencyCode)
                    ->first()->balance;
            return $amount < ($bal + $bal*0.1);
        }
        return Account::where("user_id", $this->id)
                      ->where("currency_code", $currencyCode)
                      ->where("balance", ">=", $amount)
                      ->count() == 1;
    }

    public function hasAccount($currencyCode): bool
    {
        return Account::where("user_id", $this->id)
                ->where("currency_code", $currencyCode)
                ->count() == 1;
    }

    public function hasOnlyCZKAccount(): bool
    {
        return Account::where("user_id", $this->id)
            ->where("currency_code", "!=", "CZK")
            ->count() == 0;
    }
}
