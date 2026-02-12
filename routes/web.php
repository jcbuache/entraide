<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Models\User;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\RoleMiddleware;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
        return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
/*
Route::get('/test-role', function () {
    return 'Test de rôle';
})->middleware('role:admin');

Route::get('/check-auth', function () {
    if (Auth::check()) {
        return 'User: ' . Auth::user()->name;
    }
    return 'No user authenticated';
});

Route::get('/home', function () {
    if (Auth::check()) {
        return 'Utilisateur connecté';
    } else {
        return 'Pas d\'utilisateur connecté';
    }
});
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    //Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    //Route::get('/admin/user', [UserController::class, 'index'])->name('admin.index');
    // Autres routes pour l'admin
    Route::get('/admin/help-management', [AdminController::class, 'showHelpTable'])->name('admin.helpTable');

    // Routes pour ajouter des personnes et des semaines
    Route::get('/admin/add-person', [AdminController::class, 'addPerson'])->name('admin.addPerson');
    Route::post('/admin/store-person', [AdminController::class, 'storePerson'])->name('admin.storePerson');
    
    // Route pour s'inscrire à l'aide d'une personne
    Route::post('/admin/signup-help/{person}/{week}', [UserController::class, 'signupHelp'])->name('admin.signupHelp');
    Route::post('/admin/cancel-help/{person}/{week}', [UserController::class, 'cancelHelp'])->name('admin.cancelHelp');

    // Ajout d'utilistaeur
    Route::get('/admin/add-user', [AdminController::class, 'showAddUserForm'])->name('admin.showAddUserForm');
    Route::post('/admin/add-user', [AdminController::class, 'storeUser'])->name('admin.storeUser');

    // Gestion des utilisateurs
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.showUsers');
    Route::put('/admin/users/update/{id}', [AdminController::class, 'updateUser'])->name('admin.updateUser');
    Route::put('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    // Gestion des personnes
    Route::get('/admin/persons', [AdminController::class, 'showPersons'])->name('admin.showPersons');
    Route::put('/admin/persons/update/{id}', [AdminController::class, 'updatePerson'])->name('admin.updatePerson');
    Route::put('/admin/persons/delete/{id}', [AdminController::class, 'deletePerson'])->name('admin.deletePerson');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/help-table', [UserController::class, 'showHelpTable'])->name('user.helpTable');
    Route::post('/signup-help/{person}/{week}', [UserController::class, 'signupHelp'])->name('user.signupHelp');
    Route::post('/cancel-help/{person}/{week}', [UserController::class, 'cancelHelp'])->name('user.cancelHelp');
    // Autres routes pour l'utilisateur
});

require __DIR__.'/auth.php';
