<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Eleve;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EleveController extends Controller
{
    public function showUploadForm()
    {
        return view('eleves.upload');
    }

    public function uploadEleves(Request $request)
    {
        $request->validate([
            'fichier_eleves' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240' // 10MB max
        ]);

        try {
            $file = $request->file('fichier_eleves');
            $extension = $file->getClientOriginalExtension();
            
            $elevesAjoutes = 0;
            $elevesModifies = 0;
            $elevesIgnores = 0;
            $lignesAvecErreurs = [];

            if (in_array($extension, ['csv', 'txt'])) {
                $handle = fopen($file->getPathname(), 'r');
                $ligne = 0;
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $ligne++;
                    
                    // Ignorer la première ligne (en-têtes)
                    if ($ligne === 1) continue;
                    
                    // Vérifier qu'on a bien 4 colonnes
                    if (count($data) < 4) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Format incorrect (attendu: 4 colonnes, reçu: " . count($data) . ")";
                        continue;
                    }

                    $code_massar = trim($data[0]);
                    $nom_ar = trim($data[1]);
                    $prenom_ar = trim($data[2]);
                    $classe = trim($data[3]);

                    // Validation des données
                    if (empty($code_massar) || empty($nom_ar) || empty($prenom_ar) || empty($classe)) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Données manquantes";
                        continue;
                    }

                    // Vérifier si l'élève existe déjà par code_massar
                    $eleveExistant = Eleve::where('code_massar', $code_massar)->first();
                    
                    if (!$eleveExistant) {
                        // Créer un nouvel élève
                        Eleve::create([
                            'code_massar' => $code_massar,
                            'nom_ar' => $nom_ar,
                            'prenom_ar' => $prenom_ar,
                            'classe' => $classe,
                            'score_discipline' => 100
                        ]);
                        $elevesAjoutes++;
                    } else {
                        // Vérifier si les informations ont changé
                        $changements = false;
                        $changementsDetails = [];

                        if ($eleveExistant->nom_ar !== $nom_ar) {
                            $changements = true;
                            $changementsDetails[] = "nom: {$eleveExistant->nom_ar} → {$nom_ar}";
                            $eleveExistant->nom_ar = $nom_ar;
                        }

                        if ($eleveExistant->prenom_ar !== $prenom_ar) {
                            $changements = true;
                            $changementsDetails[] = "prénom: {$eleveExistant->prenom_ar} → {$prenom_ar}";
                            $eleveExistant->prenom_ar = $prenom_ar;
                        }

                        if ($eleveExistant->classe !== $classe) {
                            $changements = true;
                            $changementsDetails[] = "classe: {$eleveExistant->classe} → {$classe}";
                            $eleveExistant->classe = $classe;
                        }

                        if ($changements) {
                            $eleveExistant->save();
                            $elevesModifies++;
                            
                            // Optionnel: logger les modifications
                            Log::info("Élève modifié - Code Massar: $code_massar - Changements: " . implode(', ', $changementsDetails));
                        } else {
                            $elevesIgnores++;
                        }
                    }
                }
                fclose($handle);
                
            } else {
                // Pour les fichiers Excel, on pourrait utiliser Maatwebsite/Laravel-Excel
                // Pour l'instant, on retourne une erreur
                return redirect()->back()->with('error', 'Le format Excel n\'est pas encore supporté. Veuillez utiliser un fichier CSV.');
            }

            $message = "Import terminé : $elevesAjoutes élèves ajoutés, $elevesModifies élèves modifiés, $elevesIgnores élèves ignorés (aucun changement).";
            
            if (!empty($lignesAvecErreurs)) {
                $message .= " " . count($lignesAvecErreurs) . " lignes avec erreurs.";
                session()->flash('erreurs_details', $lignesAvecErreurs);
            }

            return redirect()->route('eleves.upload.form')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload des élèves: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'import: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $eleves = Eleve::orderBy('classe')->orderBy('nom_ar')->get();
        return view('eleves.index', compact('eleves'));
    }
}