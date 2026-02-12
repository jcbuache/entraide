<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

@extends('layouts.app')

@section('content')
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
        <span class="close" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
    @endif

    @if (Auth::user()->role->name == 'admin')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">
                        <a href="{{ route('admin.helpTable') }}" class="btn-soft-green">Visualisation du planning</a><br/><br/>
                        <a href="{{ route('admin.showUsers') }}" class="btn-soft-green">Gestion des utilisateurs</a><br/><br/>
                        <a href="{{ route('admin.showPersons') }}" class="btn-soft-green">Gestion des personnes à aider</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="text-center p-6 text-gray-900">
                        <a href="{{ route('user.helpTable') }}" class="btn-soft-green">Gérer mes engagements</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

</x-app-layout>