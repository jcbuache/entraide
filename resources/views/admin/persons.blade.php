@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
@endif
<div class="container">
    <h1 class="page-title mb-2">Liste des Personnes à Aider</h1>
    <div class="text-center mb-4">
        <a href="{{ route('admin.addPerson') }}" class="btn-soft-green">Ajouter une personne à aider</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Ville</th>
                <th>Tâche</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($persons as $person)
                <tr>
                    <form action="{{ route('admin.updatePerson', $person->id) }}" method="POST" class="d-inline-flex">
                        @csrf
                        @method('PUT')
                        <td class="px-4 py-2">
                            <input type="text" name="name" class="form-container input" value="{{ $person->name }}" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="text" name="location" class="form-container input" value="{{ $person->location }}" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="text" name="task" class="form-container input" value="{{ $person->task }}" required>
                        </td>
                        <td class="actions">
                            <button type="submit" class="btn btn-soft-green">Sauvegarder</button>
                    </form>
                    <form action="{{ route('admin.deletePerson', $person->id) }}" method="POST" class="d-inline-flex"  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet personne ?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-highlight-soft-red">Supprimer</button>
                    </form>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
