<!DOCTYPE html>
<html>
<head>
    <title>Notification d'entraide</title>
</head>
<body>
    <h2>{{ $action === 'signup' ? 'Nouvelle inscription à l’Entraide' : 'Annulation d’Entraide' }}</h2>

    <p>Bonjour,</p>
    <p>
        L'utilisateur {{ $userName }}  
        {{ $action === 'signup' ? 's\'est inscrit pour aider' : 'a annulé son aide pour' }} 
        {{ $personName }} ({{ $task }}).
    </p>

    <p>Cordialement,</p>
    <p>Le site d'Entraide</p>
</body>
</html>
