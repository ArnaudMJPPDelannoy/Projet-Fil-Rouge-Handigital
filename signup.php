<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Inscription</title>
</head>
<body>
    <main>
        <h1>Inscription</h1>
        <form action="" method="post">
            <input type="text" name="lastname" id="lastname" placeholder="Nom" class="text-input">
            <input type="text" name="firstname" id="firstname" placeholder="Prénom" class="text-input">
            <input type="number" name="age" id="age" min="0" placeholder="Age" class="text-input">
            <h3>Genre :</h3>
            <input type="radio" name="gender" id="man" value="man"> <label for="man">Homme</label><br>
            <input type="radio" name="gender" id="woman" value="woman"> <label for="woman">Femme</label><br>
            <input type="radio" name="gender" id="other" value="other"> <label for="other">Autre</label><br>
            <input type="email" name="email" id="email" placeholder="Adresse Mail" class="text-input">
            <input type="text" name="pseudoname" id="pseudo" placeholder="Pseudonyme" class="text-input">
            <div class="pass-input">
                <input type="password" name="password" id="password" placeholder="Mot de Passe" class="text-input">
                <i class="bi bi-eye-fill pass-show"></i>
            </div>
            <input type="password" name="pass-check" id="pass-check" placeholder="Confirmer Mot de Passe" class="text-input">
            <input type="submit" value="Inscription" class="button">
        </form>
        <h4>Vous avez déjà un compte ?</h4>
        <a href="./" class="button">Se connecter</a>
    </main>
</body>
</html>