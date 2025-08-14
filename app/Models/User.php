<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // Contoh relasi: satu user bisa membuat banyak backlog dan repairs
    public function backlogs()
    {
        return $this->hasMany(Backlog::class, 'made_by', 'id');
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class, 'nama_pembuat', 'name');
    }
}
