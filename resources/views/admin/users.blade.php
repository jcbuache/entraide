@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
@endif
<div class="container">
    <h1 class="page-title mb-2">Gestion des utilisateurs</h1>
    <div class="text-center mb-4">
        <a href="{{ route('admin.showAddUserForm') }}" class="btn-soft-green">Ajouter un utilisateur</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <form action="{{ route('admin.updateUser', $user->id) }}" method="POST" class="d-inline-flex">
                        @csrf
                        @method('PUT')
                        <td class="px-4 py-2">
                            <input type="text" name="name" class="form-container input" value="{{ $user->name }}" required>
                        </td>
                        <td class="px-4 py-2">
                            <input type="email" name="email" class="form-container input" value="{{ $user->email }}" required>
                        </td>
                        <td class="px-4 py-2">
                            <select name="role" class="form-container select">
                                <option value="user" {{ $user->role->name == 'user' ? 'selected' : '' }}>Utilisateur</option>
                                <option value="admin" {{ $user->role->name == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                        </td>
                        <td class="actions">
                            <button type="submit" class="btn btn-soft-green">Sauvegarder</button>
                    </form>
                    <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" class="d-inline-flex" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
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
