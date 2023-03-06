<?php
// Add code to redirect if they are already connected.
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Connexion</title>
</head>
<body>
    <main>
        <h1>Connexion</h1>
        <form action="" method="post">
            <input type="text" name="username" id="username" placeholder="Identifiant" class="text-input">
            <input type="password" name="password" id="password" placeholder="Mot de Passe" class="text-input">
            <i class="bi bi-eye-fill" id="passShow"></i>
            <input type="submit" value="Se connecter" class="button">
        </form>
        <a href="#">Mot de passe oublié ?</a>
        <h3>Pas encore inscrit ?</h3>
        <a href="" class="button">Inscription</a>
    </main>
</body>
</html>