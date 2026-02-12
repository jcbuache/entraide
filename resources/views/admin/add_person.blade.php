@extends('layouts.app')

@section('content')
    <h1 class="page-title">Ajouter une personne à aider</h1>

    <!-- Affiche un message de succès -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="form-container">
        <form action="{{ route('admin.storePerson') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Nom :</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Ville :</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" required>
                @error('location')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="task">Tâche :</label>
                <input type="text" name="task" id="task" value="{{ old('task') }}" required>
                @error('task')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary mt-3">Ajouter</button>
        </form>
    </div>
@endsection
