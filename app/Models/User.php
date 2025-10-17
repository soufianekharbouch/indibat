<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'role',
        'username',
        'password',
        'matiere'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function rapports()
    {
        return $this->hasMany(Rapport::class, 'prof_id');
    }

    public function isRoot()
    {
        return $this->role === 'root';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isProf()
    {
        return $this->role === 'prof';
    }

    public function isMotasarrif()
    {
        return $this->role === 'motasarrif';
    }
}