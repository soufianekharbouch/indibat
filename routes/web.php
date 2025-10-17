<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ComportementController;
use App\Http\Controllers\ConseilController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search-eleves', [DashboardController::class, 'searchEleves'])->name('search.eleves');
    Route::get('/eleve/{id}', [DashboardController::class, 'showEleve'])->name('eleve.show');
    
    Route::get('/rapport/create/{eleve}', [RapportController::class, 'create'])->name('rapport.create');
    Route::post('/rapport', [RapportController::class, 'store'])->name('rapport.store');
    
    // Routes pour les conseils de discipline
    Route::get('/conseils', [ConseilController::class, 'index'])->name('conseils.index');
    Route::get('/conseils/create/{eleve}', [ConseilController::class, 'create'])->name('conseils.create');
    Route::post('/conseils', [ConseilController::class, 'store'])->name('conseils.store');
    Route::get('/conseils/{id}', [ConseilController::class, 'show'])->name('conseils.show');
    Route::post('/conseils/{id}/avis', [ConseilController::class, 'donnerAvis'])->name('conseils.donner-avis');
    Route::post('/conseils/{id}/fermer', [ConseilController::class, 'fermer'])->name('conseils.fermer');
     Route::get('/mes-rapports', [RapportController::class, 'mesRapports'])->name('mes-rapports');
    Route::get('/mes-conseils', [ConseilController::class, 'mesConseils'])->name('mes-conseils');
    Route::get('/liste-profs', [UserController::class, 'listeProfs'])->name('liste-profs');
    Route::get('/statistiques', [DashboardController::class, 'statistiques'])->name('statistiques');
    
    // Routes pour la gestion des comportements (root seulement)
    Route::middleware(['can:root'])->group(function () {
        Route::get('/comportements', [ComportementController::class, 'index'])->name('comportements.index');
        Route::get('/comportements/create', [ComportementController::class, 'create'])->name('comportements.create');
        Route::post('/comportements', [ComportementController::class, 'store'])->name('comportements.store');
        Route::get('/comportements/{comportement}/edit', [ComportementController::class, 'edit'])->name('comportements.edit');
        Route::put('/comportements/{comportement}', [ComportementController::class, 'update'])->name('comportements.update');
        Route::delete('/comportements/{comportement}', [ComportementController::class, 'destroy'])->name('comportements.destroy');
    });
});