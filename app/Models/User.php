<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\CustomVerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'Name',
        'Surname',
        'email',
        'Mobile',
        'Phone',
        'Address',
        'City',
        'County',
        'Country',
        'Lang',
        'CompanyName',
        'CompanyPhone',
        'CompanyAddress',
        'CompanyCity',
        'CompanyCounty',
        'CompanyCountry',
        'CompanyVAT',
        'Active',
        'UserType',
        'Notes',
        'password',
    ]; 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sims()
    {
        return $this->hasMany(Sim::class);
    }

    /**
     * Aggiunto ora questa funzione per la modifica del template email Reset Password
     */
    public function sendPasswordResetNotification($token)
    {
    $this->notify(new CustomResetPassword($token));
    }

    /**
     * Aggiunto ora questa funzione per la modifica del template email Verifica La Tua Email
     */
    public function sendEmailVerificationNotification() 
    {
        $this->notify(new CustomVerifyEmailNotification);
    }
}
