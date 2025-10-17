<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rapport;
use App\Models\Conseil;

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
}