@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajouter une semaine</h2>

    <form method="POST" action="{{ route('admin.storeWeek') }}">
        @csrf
        <div class="mb-3">
            <label for="year" class="form-label">Année</label>
            <input type="number" name="year" id="year" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="week_number" class="form-label">Numéro de semaine</label>
            <input type="number" name="week_number" id="week_number" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
