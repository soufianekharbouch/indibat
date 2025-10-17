<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Eleve;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Créer l'utilisateur root
        User::create([
            'nom' => 'Root',
            'prenom' => 'Admin',
            'role' => 'root',
            'username' => 'root',
            'password' => Hash::make('password123'),
            'matiere' => 'Administration'
        ]);

        // Créer des professeurs
        $professeurs = [
            ['nom' => 'Alaoui', 'prenom' => 'Ahmed', 'matiere' => 'Mathématiques'],
            ['nom' => 'Benali', 'prenom' => 'Fatima', 'matiere' => 'Physique'],
            ['nom' => 'Chraibi', 'prenom' => 'Mohamed', 'matiere' => 'Chimie'],
            ['nom' => 'Dahmani', 'prenom' => 'Khadija', 'matiere' => 'SVT'],
            ['nom' => 'El Fassi', 'prenom' => 'Hassan', 'matiere' => 'Histoire-Géographie'],
            ['nom' => 'Farsi', 'prenom' => 'Amina', 'matiere' => 'Français'],
            ['nom' => 'Gharbi', 'prenom' => 'Rachid', 'matiere' => 'Anglais'],
            ['nom' => 'Hassani', 'prenom' => 'Samira', 'matiere' => 'Philosophie'],
            ['nom' => 'Idrissi', 'prenom' => 'Youssef', 'matiere' => 'Éducation Islamique'],
            ['nom' => 'Jabri', 'prenom' => 'Nadia', 'matiere' => 'Informatique'],
            ['nom' => 'Kamil', 'prenom' => 'Karim', 'matiere' => 'Éducation Physique'],
            ['nom' => 'Lahrach', 'prenom' => 'Leila', 'matiere' => 'Arts Plastiques'],
            ['nom' => 'Mansouri', 'prenom' => 'Mustapha', 'matiere' => 'Musique'],
            ['nom' => 'Naciri', 'prenom' => 'Noura', 'matiere' => 'Économie'],
            ['nom' => 'Ouazzani', 'prenom' => 'Omar', 'matiere' => 'Droit'],
            ['nom' => 'Rahali', 'prenom' => 'Rachida', 'matiere' => 'Sociologie'],
            ['nom' => 'Saidi', 'prenom' => 'Said', 'matiere' => 'Technologie'],
            ['nom' => 'Tazi', 'prenom' => 'Touria', 'matiere' => 'Espagnol'],
            ['nom' => 'Zahir', 'prenom' => 'Zineb', 'matiere' => 'Allemand'],
            ['nom' => 'Bennani', 'prenom' => 'Bouchra', 'matiere' => 'Arabe']
        ];

        foreach ($professeurs as $prof) {
            User::create([
                'nom' => $prof['nom'],
                'prenom' => $prof['prenom'],
                'role' => 'prof',
                'username' => strtolower($prof['prenom'][0] . $prof['nom']),
                'password' => Hash::make('password123'),
                'matiere' => $prof['matiere']
            ]);
        }

        // Créer des élèves avec des noms marocains en arabe
        $nomsArabes = [
            'العلوي', 'البوغالي', 'الشاوي', 'الدرقاوي', 'الزمام', 'العفاني', 'الفاسي', 'القباج',
            'الكبير', 'المغاري', 'الناصري', 'الهبطي', 'الودغيري', 'بلمليح', 'بنعبدالله', 'بنونة',
            'تاكة', 'جلول', 'حماني', 'خداج', 'دليم', 'ركراكة', 'زنيبر', 'سالم', 'شاكير', 'صابر',
            'طاطا', 'عبدالوهاب', 'عكاشة', 'غزالي', 'فكري', 'قصري', 'لكحل', 'مزيان', 'نكادي'
        ];

        $prenomsArabes = [
            'محمد', 'أحمد', 'مصطفى', 'علي', 'عمر', 'يوسف', 'خالد', 'حمزة', 'بشير', 'أنس',
            'فاطمة', 'خديجة', 'عائشة', 'مريم', 'زينب', 'سارة', 'نورة', 'هدى', 'إيمان', 'لمياء',
            'حسن', 'حسين', 'إبراهيم', 'أسامة', 'راشد', 'سعيد', 'طارق', 'وليد', 'يحيى', 'زيد',
            'أسماء', 'بثينة', 'جميلة', 'حليمة', 'دعاء', 'رغدة', 'سميرة', 'عالية', 'غادة', 'ليلى'
        ];

        $classes = ['1BAC SM', '1BAC PC', '1BAC SVT', '2BAC SM', '2BAC PC', '2BAC SVT', 'TC SM', 'TC PC', 'TC SVT'];

        for ($i = 1; $i <= 100; $i++) {
            $nom = $nomsArabes[array_rand($nomsArabes)];
            $prenom = $prenomsArabes[array_rand($prenomsArabes)];
            $classe = $classes[array_rand($classes)];
            $codeMassar = 'M' . str_pad($i, 8, '0', STR_PAD_LEFT);

            Eleve::create([
                'code_massar' => $codeMassar,
                'nom_ar' => $nom,
                'prenom_ar' => $prenom,
                'classe' => $classe,
            ]);
        }

        // Créer un admin et un motasarrif
        User::create([
            'nom' => 'Admin',
            'prenom' => 'System',
            'role' => 'admin',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'matiere' => 'Administration'
        ]);

        User::create([
            'nom' => 'Motasarrif',
            'prenom' => 'Direction',
            'role' => 'motasarrif',
            'username' => 'motasarrif',
            'password' => Hash::make('password123'),
            'matiere' => 'Direction'
        ]);

        // Ajouter les comportements par défaut
        $comportements = [
            ['nom_fr' => 'perturbation', 'nom_ar' => 'إثارة الفوضى في القسم', 'points_retires' => 5, 'categorie' => 'classe'],
            ['nom_fr' => 'violence', 'nom_ar' => 'عنف تجاه الزملاء', 'points_retires' => 10, 'categorie' => 'grave'],
            ['nom_fr' => 'insolence', 'nom_ar' => 'وقاحة تجاه الأستاذ', 'points_retires' => 8, 'categorie' => 'grave'],
            ['nom_fr' => 'absence', 'nom_ar' => 'غياب غير مبرر', 'points_retires' => 7, 'categorie' => 'assiduite'],
            ['nom_fr' => 'retard', 'nom_ar' => 'تأخر عن الحصة', 'points_retires' => 3, 'categorie' => 'assiduite'],
            ['nom_fr' => 'tricherie', 'nom_ar' => 'غش في الامتحان', 'points_retires' => 15, 'categorie' => 'grave'],
            ['nom_fr' => 'negligence', 'nom_ar' => 'إهمال الواجبات', 'points_retires' => 4, 'categorie' => 'travail'],
            ['nom_fr' => 'telephone', 'nom_ar' => 'استعمال الهاتف في القسم', 'points_retires' => 6, 'categorie' => 'classe'],
            ['nom_fr' => 'tenue', 'nom_ar' => 'عدم احترام اللباس الموحد', 'points_retires' => 3, 'categorie' => 'tenue'],
            ['nom_fr' => 'mensonge', 'nom_ar' => 'كذب على المسؤولين', 'points_retires' => 8, 'categorie' => 'grave'],
        ];

        foreach ($comportements as $comportement) {
            \App\Models\Comportement::create($comportement);
        }
    }
}