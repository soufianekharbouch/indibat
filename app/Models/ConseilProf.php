<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConseilProf extends Model
{
    use HasFactory;

    protected $table = 'conseil_prof';

    protected $fillable = [
        'conseil_id',
        'prof_id',
        'a_repondu',
        'avis',
        'justification',
        'repondu_le'
    ];

    protected $casts = [
        'a_repondu' => 'boolean',
        'repondu_le' => 'datetime'
    ];

    public function conseil()
    {
        return $this->belongsTo(Conseil::class);
    }

    public function prof()
    {
        return $this->belongsTo(User::class, 'prof_id');
    }
}