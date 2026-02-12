<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" />
    
    <!-- Styles -->
    <!--@vite(['resources/css/app.css', 'resources/js/app.js'])-->
    <link href="{{ asset('build/assets/app-BFbpH5sI.css') }}" rel="stylesheet">
    <script src="{{ asset('build/assets/app-DI6-W-r-.js') }}" defer></script>

    <style>
        .welcome-container {
            text-align: center;
            padding: 2rem;
            background-color: #f9f9f9;
        }
        .welcome-logo {
            width: 300px;
            height: auto;
            margin: 0 auto; 
            display: block;
            margin-bottom: 1rem;
        }
        .welcome-heading {
            font-size: 2rem;
            color: #333;
            margin-bottom: 1rem;
            font-weight: bold;
        }
        .welcome-text {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .welcome-btn {
            background-color: #4caf50;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
        }
        .welcome-btn:hover {
            background-color: #43a047;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <!-- Logo -->
        <img src="{{ asset('images/Logo-MB-rond.png') }}" alt="Logo" class="welcome-logo">

        <!-- Heading -->
        <h1 class="welcome-heading">Bienvenue sur le site d'entraide Sainte Marguerite Bays</h1>

        <!-- Description -->
        <p class="welcome-text">
            Plateforme de gestion d'entraide hebdomadaire.</br></br> Connectez-vous pour gérer vos engagements et aider les personnes dans le besoin.
        </p>

        <!-- Login Button -->
        @if (Route::has('login'))
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="welcome-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="welcome-btn">Connexion</a>
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
