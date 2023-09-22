<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class LupaPassword extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'lupa_passwords';
    protected $primaryKey = 'email';
    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'otp',
        'created_at'
    ];
}
