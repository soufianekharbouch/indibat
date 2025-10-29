<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rapport;
use App\Models\Conseil;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function listeProfs()
    {
        $profs = User::where('role', 'prof')
            ->withCount(['rapports as total_rapports'])
            ->get()
            ->map(function($prof) {
                $prof->total_avis = Conseil::whereHas('profs', function($query) use ($prof) {
                    $query->where('prof_id', $prof->id)->where('a_repondu', true);
                })->count();
                
                $prof->total_conseils = Conseil::whereHas('profs', function($query) use ($prof) {
                    $query->where('prof_id', $prof->id);
                })->count();
                
                return $prof;
            });

        return view('users.liste-profs', compact('profs'));
    }

    public function showUploadForm()
    {
        if (!auth()->user()->isRoot()) {
            abort(403, 'Unauthorized action.');
        }

        return view('users.upload');
    }

    public function uploadUsers(Request $request)
    {
        if (!auth()->user()->isRoot()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'fichier_users' => 'required|file|mimes:csv,txt|max:10240' // 10MB max
        ]);

        try {
            $file = $request->file('fichier_users');
            $extension = $file->getClientOriginalExtension();
            
            $usersAjoutes = 0;
            $usersModifies = 0;
            $usersIgnores = 0;
            $lignesAvecErreurs = [];

            if (in_array($extension, ['csv', 'txt'])) {
                $handle = fopen($file->getPathname(), 'r');
                $ligne = 0;
                
                // Vérifier l'encodage et sauter BOM si nécessaire
                $firstLine = fgets($handle);
                rewind($handle);
                if (substr($firstLine, 0, 3) == "\xEF\xBB\xBF") {
                    // Fichier avec BOM, on avance de 3 octets
                    fseek($handle, 3);
                }
                
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $ligne++;
                    
                    // Ignorer la première ligne (en-têtes)
                    if ($ligne === 1) continue;
                    
                    // Vérifier qu'on a bien 6 colonnes
                    if (count($data) < 6) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Format incorrect (attendu: 6 colonnes, reçu: " . count($data) . ")";
                        continue;
                    }

                    $nom = trim($data[0]);
                    $prenom = trim($data[1]);
                    $matiere = trim($data[2]);
                    $username = trim($data[3]);
                    $password = trim($data[4]);
                    $role = trim($data[5]);

                    // Validation des données
                    if (empty($nom) || empty($prenom) || empty($username) || empty($password) || empty($role)) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Données manquantes";
                        continue;
                    }

                    // Validation du rôle
                    $rolesValides = ['prof', 'admin', 'root', 'motasarrif'];
                    if (!in_array($role, $rolesValides)) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Rôle invalide '$role'. Rôles valides: " . implode(', ', $rolesValides);
                        continue;
                    }

                    // Validation du mot de passe
                    if (strlen($password) < 4) {
                        $lignesAvecErreurs[] = "Ligne $ligne: Mot de passe trop court (minimum 4 caractères)";
                        continue;
                    }

                    // Vérifier si l'utilisateur existe déjà par username
                    $userExistant = User::where('username', $username)->first();
                    
                    if (!$userExistant) {
                        // Créer un nouvel utilisateur
                        User::create([
                            'nom' => $nom,
                            'prenom' => $prenom,
                            'matiere' => $matiere,
                            'username' => $username,
                            'password' => Hash::make($password),
                            'role' => $role
                        ]);
                        $usersAjoutes++;
                    } else {
                        // Vérifier si les informations ont changé
                        $changements = false;

                        if ($userExistant->nom !== $nom) {
                            $changements = true;
                            $userExistant->nom = $nom;
                        }

                        if ($userExistant->prenom !== $prenom) {
                            $changements = true;
                            $userExistant->prenom = $prenom;
                        }

                        if ($userExistant->matiere !== $matiere) {
                            $changements = true;
                            $userExistant->matiere = $matiere;
                        }

                        if ($userExistant->role !== $role) {
                            $changements = true;
                            $userExistant->role = $role;
                        }

                        // Vérifier si le mot de passe a changé (en clair dans le CSV)
                        if (!Hash::check($password, $userExistant->password)) {
                            $changements = true;
                            $userExistant->password = Hash::make($password);
                        }

                        if ($changements) {
                            $userExistant->save();
                            $usersModifies++;
                        } else {
                            $usersIgnores++;
                        }
                    }
                }
                fclose($handle);
                
            } else {
                return redirect()->back()->with('error', 'Le format de fichier n\'est pas supporté. Veuillez utiliser un fichier CSV.');
            }

            $message = "Import terminé : $usersAjoutes utilisateurs ajoutés, $usersModifies utilisateurs modifiés, $usersIgnores utilisateurs ignorés (aucun changement).";
            
            if (!empty($lignesAvecErreurs)) {
                $message .= " " . count($lignesAvecErreurs) . " lignes avec erreurs.";
                session()->flash('erreurs_details', $lignesAvecErreurs);
            }

            return redirect()->route('users.upload.form')->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload des utilisateurs: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'import: ' . $e->getMessage());
        }
    }
}