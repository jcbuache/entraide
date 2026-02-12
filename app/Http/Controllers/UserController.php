<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\PersonInNeed;
use App\Models\Week;
use App\Models\HelpSignup;
use App\Models\User;

use App\Mail\HelpSignupNotification;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log; 

class UserController extends Controller
{
    public function index()
    {
        // Logique pour afficher les utilisateurs
        $user = Auth::user();
        if ($user && $user->role) {
            return 'Le rôle de l\'utilisateur est : ' . $user->role->name;
        } else {
            return 'Utilisateur ou rôle non trouvé.';
        }
    }

    // Autres méthodes pour permettre aux utilisateurs de s'inscrire
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

        return view('user.help_table', compact('peopleInNeed', 'weeks', 'signups'));
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

    public function signupHelp($personId, $weekId)
    {
        $person = PersonInNeed::findOrFail($personId);

        HelpSignup::create([
            'user_id' => Auth::id(),
            'person_in_need_id' => $personId,
            'week_id' => $weekId,
        ]);

        $admins = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            if ($admin->email != 'jean-christophe@buache.net'){
                try {
                    Mail::to($admin->email)->send(new HelpSignupNotification(Auth::user(), $person, 'signup'));
                } catch (\Exception $e) {
                    Log::error("Could not send mail to $admin->email.");
                }
            }
        }

        //return redirect()->route('user.helpTable')->with('success', 'Inscription effectuée avec succès.');
        return redirect()->back()->with('success', 'Inscription effectuée avec succès.');
    }

    public function cancelHelp($personId, $weekId)
    {
        $person = PersonInNeed::findOrFail($personId);

        $deleted = HelpSignup::where('person_in_need_id', $personId)
                         ->where('week_id', $weekId)
                         ->where('user_id', Auth::id()) // Récupère l'utilisateur connecté
                         ->delete();

        if ($deleted) {
            $admins = User::whereHas('role', function($query) {
                $query->where('name', 'admin');
            })->get();

            foreach ($admins as $admin) {
                if ($admin->email != 'jean-christophe@buache.net'){
                    try {
                        Mail::to($admin->email)->send(new HelpSignupNotification(Auth::user(), $person, 'cancel'));
                    } catch (\Exception $e) {
                        Log::error("Could not send mail to $admin->email.");
                    }
                }
            }

            return redirect()->back()->with('success', 'L\'inscription a été supprimée avec succès.');
        }

        return redirect()->back()->with('warn', 'Aucune inscription trouvée pour cette aide.');
    }
}
