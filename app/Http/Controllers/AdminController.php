<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\PersonInNeed;
use App\Models\User;
use App\Models\Role;
use App\Models\Week;
use App\Models\HelpSignup;

use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    public function index()
    {
        // Logique pour afficher les demandeurs d'aide
        $user = Auth::user(); // Récupère l'utilisateur actuellement connecté

        if ($user && $user->role) {
            return 'Le rôle de l\'utilisateur est : ' . $user->role->name;
        } else {
            return 'Utilisateur ou rôle non trouvé.';
        }
        
    }
    // Autres méthodes pour gérer les demandeurs d'aide

    // Méthode pour afficher le tableau d'administration
    public function showHelpTable()
    {
        $today = Carbon::today();
        $weeksToGenerate = 8;

        $existingWeeks = Week::where('start_date', '>=', $today)->orderBy('start_date')->get();
        if ($existingWeeks->count() < $weeksToGenerate) {
            $this->generateUpcomingWeeks($weeksToGenerate, $today);
            $existingWeeks = Week::where('start_date', '>=', $today)->orderBy('start_date')->get();
        }

        $peopleInNeed = PersonInNeed::all();  // Récupère toutes les personnes en besoin d'aide
        //$weeks = Week::orderBy('year')->orderBy('week_number')->get();  // Récupère toutes les semaines
        $weeks = Week::whereDate('start_date', '>=', now()->subWeek(1))
        ->orderBy('start_date')
        ->get();
        $signups = HelpSignup::all();  // Récupère toutes les inscriptions d'aide

        Log::debug('People in Need: ', $peopleInNeed->toArray());
        Log::debug('Weeks: ', $weeks->toArray());
        Log::debug('Signups: ', $signups->toArray());

        return view('admin.help_table', compact('peopleInNeed', 'weeks', 'signups'));
    }

    private function generateUpcomingWeeks($weeksToGenerate, $startDate)
    {
        // Trouver le prochain lundi
        $nextMonday = $startDate->nextWeekday(1);

        for ($i = 0; $i < $weeksToGenerate; $i++) {
            // Ajouter des semaines à partir du prochain lundi
            $date = $nextMonday->copy()->addWeeks($i);
            // Vérifier si la semaine existe déjà pour cette date
            if (!Week::where('start_date', $date->startOfWeek())->exists()) {
                Week::create(['start_date' => $date->startOfWeek()]);
            }
        }
    }

    // Méthode pour afficher la page d'ajout de personnes
    public function addPerson()
    {
        return view('admin.add_person');
    }

    // Méthode pour enregistrer une nouvelle personne
    public function storePerson(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'task' => 'required|string|max:255',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'location.required' => 'La ville est obligatoire.',
            'task.required' => 'La tâche est obligatoire.',
        ]);

        PersonInNeed::create($request->only('name', 'location', 'task'));

        return redirect()->route('admin.showPersons')->with('success', 'Personne ajoutée avec succès.');
    }

    // Ajout d'utilisateur
    public function showAddUserForm()
    {
        return view('admin.add_user');  // Vue que nous allons créer pour le formulaire
    }

    public function storeUser(Request $request)
    {
        // Valider les données entrantes
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user', // Choix du rôle
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'L’adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Créer un nouvel utilisateur
        $role = Role::where('name', $request->role)->first();
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id
        ]);

        return redirect()->route('admin.showUsers')->with('success', 'Utilisateur ajouté avec succès');
    }

    // Gestion des utilisateurs
    public function showUsers()
    {
        $users = User::with('role')->get(); // Supposant que 'role' est la relation définie dans User pour le modèle Role
        $roles = Role::all(); // Récupère tous les rôles

        return view('admin.users', compact('users', 'roles'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = Role::where('name', $request->input('role'))->first();
        
        if (!$role) {
            return redirect()->route('admin.showUsers')->with('error', 'Le rôle spécifié est invalide.');
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $role->id
        ]);

        return redirect()->route('admin.showUsers')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id); // Trouver l'utilisateur par son ID
        $user->helpSignups()->delete();
        $user->delete(); // Supprimer l'utilisateur

        return redirect()->route('admin.showUsers')->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Gestion des personnes
    public function showPersons()
    {
        $persons = PersonInNeed::all();

        return view('admin.persons', compact('persons'));
    }

    public function updatePerson(Request $request, $id)
    {
        Log::debug('Received : ', $request->toArray());
        $person = PersonInNeed::findOrFail($id);
        $person->update([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'task' => $request->input('task')
        ]);

        return redirect()->route('admin.showPersons')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function deletePerson($id)
    {
        $person = PersonInNeed::findOrFail($id);
        $person->helpSignups()->delete();
        $person->delete();

        return redirect()->route('admin.showPersons')->with('success', 'Personne à aider supprimée avec succès.');
    }
}
