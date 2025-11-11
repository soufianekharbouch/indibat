<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Decision extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'motasarrif_id',
        'decision_date',
        'decision_time',
        'choices',
        'details',
    ];

    protected $casts = [
        'choices' => 'array',
        'decision_date' => 'date',
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }
    public function motasarrif()
    {
        return $this->belongsTo(User::class, 'motasarrif_id');
    }
}
