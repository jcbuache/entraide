<!-- resources/views/admin/help_table.blade.php -->
@extends('layouts.app')

@section('content')
    <h1 class="page-title mb-2">Visualisation du planning</h1>
    
    <!-- Tableau de gestion des semaines et des personnes à aider -->
    <table class="table">
        <thead>
            <tr>
                <th>Personnes à aider</th>
                <th>Tâche</th>
                @foreach ($weeks as $week)
                    <th>Semaine commençant le <br/>{{ \Carbon\Carbon::parse($week->start_date)->format('d.m.Y') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($peopleInNeed as $person)
                <tr>
                    <td>{{ $person->name }} ({{ $person->location}})</td>
                    <td>{{ $person->task }}</td>
                    @foreach ($weeks as $week)
                        <td>
                            <!-- Vérifiez si un utilisateur a signé pour aider cette personne cette semaine -->
                            @php
                                $signup = $signups->first(function ($signup) use ($person, $week) {
                                    return $signup->person_in_need_id == $person->id && $signup->week_id == $week->id;
                                });
                                $userSignup = $signup && $signup->user_id == Auth::id();
                            @endphp
                        
                            @if ($userSignup)
                                <span class="badge bg-user-confirmed">Vous vous êtes engagé</span>
                                <form action="{{ route('admin.cancelHelp', ['person' => $person->id, 'week' => $week->id]) }}" method="POST">
                                    @csrf
                                <button type="submit" class="btn-transparent-red">Me désinscrire</button>
                                </form>
                            @elseif ($signup)
                                <span class="badge bg-other-confirmed">{{$signup->user->name}}</span>
                            @else
                                <form action="{{ route('admin.signupHelp', ['person' => $person->id, 'week' => $week->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-highlight-yellow btn-warning">S'inscrire</button>
                                </form>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
