<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conseil extends Model
{
    use HasFactory;

    protected $fillable = [
        'eleve_id',
        'admin_id',
        'raison_principale',
        'description',
        'date_fermeture',
        'statut',
        'decision_finale',
        'reinitialiser_score'
    ];

    protected $casts = [
        'date_fermeture' => 'date',
        'reinitialiser_score' => 'boolean'
    ];

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function profs()
    {
        return $this->belongsToMany(User::class, 'conseil_prof', 'conseil_id', 'prof_id')
                    ->withPivot('a_repondu', 'avis', 'justification', 'repondu_le')
                    ->withTimestamps();
    }

    public function reponses()
    {
        return $this->hasMany(ConseilProf::class);
    }

    // Vérifier si le conseil est en retard
    public function getEstEnRetardAttribute()
    {
        if ($this->date_fermeture && $this->statut === 'ouvert') {
            return Carbon::now()->gt($this->date_fermeture);
        }
        return false;
    }

    // Compter les réponses reçues
    public function getNombreReponsesAttribute()
    {
        return $this->profs()->wherePivot('a_repondu', true)->count();
    }

    // Compter le total des profs concernés
    public function getTotalProfsAttribute()
    {
        return $this->profs()->count();
    }

    // Vérifier si un prof a répondu (méthode corrigée)
    public function profARepondu($profId)
    {
        return $this->profs()->where('prof_id', $profId)->wherePivot('a_repondu', true)->exists();
    }

    // Accessor pour prof_a_repondu (compatibilité avec les vues)
    public function getProfAReponduAttribute()
    {
        if (auth()->check()) {
            return $this->profARepondu(auth()->id());
        }
        return false;
    }

    // Obtenir les avis possibles
    public static function getAvisPossibles()
    {
        return [
            'إنذار شفهي / تذكير بالواجبات' => 1,
            'إنذار خطي وتوثيق في الملف' => 2,
            'حجز مطوّل / منع من الفسحة' => 3,
            'استدعاء أولياء الأمور / اتصال بالوالدين' => 4,
            'توقيع عقد سلوك مع الطالب' => 5,
            'أعمال خدمة مدرسية / ساعات إصلاح' => 6,
            'حرمان من بعض الحقوق / المنع من الخروج والمشاركات والأنشطة' => 7,
            'الإيقاف المؤقت القصير (أيام إلى أسبوعين)' => 8,
            'الإقصاء المؤقت الطويل (أسابيع/أشهر)' => 9,
            'الإقصاء النهائي' => 10,
        ];
    }

    // Fermer le conseil
    public function fermer($decisionFinale, $reinitialiserScore = false)
    {
        $this->statut = 'ferme';
        $this->decision_finale = $decisionFinale;
        $this->reinitialiser_score = $reinitialiserScore;
        $this->save();

        // Si on décide de réinitialiser le score, supprimer les rapports antérieurs
        if ($reinitialiserScore) {
            Rapport::where('eleve_id', $this->eleve_id)
                   ->where('created_at', '<=', $this->created_at)
                   ->delete();
        }
    }

    // Scope pour les conseils ouverts
    public function scopeOuverts($query)
    {
        return $query->where('statut', 'ouvert');
    }

    // Scope pour les conseils où le prof n'a pas répondu
    public function scopeWhereProfNaPasRepondu($query, $profId)
    {
        return $query->whereHas('profs', function($q) use ($profId) {
            $q->where('prof_id', $profId)->where('a_repondu', false);
        });
    }
}