<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Verify if the user has accounts.
     * 
     * @return bool
     */
    public function hasAccounts(): bool
    {
        return $this->accounts()->exists();
    }

    /**
     * Return the first account of the user.
     *
     * @return Account|null
     */
    public function firstAccount()
    {
        return $this->accounts()->first();
    }
}
