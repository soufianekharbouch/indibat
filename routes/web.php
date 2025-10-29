<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ComportementController;
use App\Http\Controllers\ConseilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\StatistiqueController;
use App\Http\Controllers\StatistiqueVisiteController;
use App\Http\Controllers\ProfilController;
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search-eleves', [DashboardController::class, 'searchEleves'])->name('search.eleves');
    Route::get('/eleve/{id}', [DashboardController::class, 'showEleve'])->name('eleve.show');
    
    Route::get('/rapport/create/{eleve}', [RapportController::class, 'create'])->name('rapport.create');
    Route::post('/rapport', [RapportController::class, 'store'])->name('rapport.store');
    Route::get('/rapport/confirmation/{rapport}', [RapportController::class, 'confirmation'])->name('rapport.confirmation'); // Nouvelle route
    
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
    Route::get('/statistiques', [StatistiqueController::class, 'index'])->name('statistiques');
    Route::get('/eleves/upload', [EleveController::class, 'showUploadForm'])->name('eleves.upload.form');
    Route::post('/eleves/upload', [EleveController::class, 'uploadEleves'])->name('eleves.upload');
    Route::get('/eleves', [EleveController::class, 'index'])->name('eleves.index');

    Route::get('/statistiques-visites', [StatistiqueVisiteController::class, 'index'])->name('statistiques-visites.index');
    Route::get('/statistiques-visites/details', [StatistiqueVisiteController::class, 'details'])->name('statistiques-visites.details');

    Route::get('/profil/change-password', [ProfilController::class, 'showChangePasswordForm'])->name('profil.change-password');
    Route::post('/profil/change-password', [ProfilController::class, 'changePassword'])->name('profil.change-password');

    Route::get('/users/upload', [UserController::class, 'showUploadForm'])->name('users.upload.form');
    Route::post('/users/upload', [UserController::class, 'uploadUsers'])->name('users.upload');

});