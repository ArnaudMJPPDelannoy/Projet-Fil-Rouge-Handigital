<?php
require "scripts/functions.php";
if (isSetAndNotEmptyObject($_POST, "lastname") && isSetAndNotEmptyObject($_POST, "firstname") && isSetAndNotEmptyObject($_POST, "age") && isSetAndNotEmptyObject($_POST, "gender") && isSetAndNotEmptyObject($_POST, "email") && isSetAndNotEmptyObject($_POST, "pseudoname") && isSetAndNotEmptyObject($_POST, "password") && isSetAndNotEmptyObject($_POST, "pass-check")) {
    $password = strip_tags($_POST["password"]);
    $passCheck = strip_tags($_POST["pass-check"]);
    if ($password != $passCheck) {
        $error = "Les mots de passes doivent être identiques.";
    } else {
        require "scripts/connect.php";
        $userRepo = new UsersRepository($pdo);
        $username = strip_tags($_POST["pseudoname"]);
        if ($userRepo->exists($username)) {
            $error = "Un utilisateur avec ce pseudonyme existe déjà.";
        } else {
            $lastname = strip_tags($_POST["lastname"]);
            $firstname = strip_tags($_POST["firstname"]);
            $age = strip_tags($_POST["age"]);
            $gender = strip_tags($_POST["gender"]);
            $email = strip_tags($_POST["email"]);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $user = new User([
                "lastName" => $lastname,
                "firstName" => $firstname,
                "age" => $age,
                "gender" => $gender,
                "email" => $email,
                "userName" => $username,
                "password" => $hashedPassword,
            ]);
            $userRepo->add($user);

            header("Location:./");
        }
    }
}
?>

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
        <h3 class="error"><?php if (isset($error) && !empty($error)) echo $error ?></h3>
        <form action="" method="post">
            <input type="text" name="lastname" id="lastname" placeholder="Nom" class="text-input" required>
            <input type="text" name="firstname" id="firstname" placeholder="Prénom" class="text-input" required>
            <input type="number" name="age" id="age" min="0" placeholder="Age" class="text-input" required>
            <h3>Genre :</h3>
            <input type="radio" name="gender" id="man" value="man" required> <label for="man">Homme</label><br>
            <input type="radio" name="gender" id="woman" value="woman"> <label for="woman">Femme</label><br>
            <input type="radio" name="gender" id="other" value="other"> <label for="other">Autre</label><br>
            <input type="email" name="email" id="email" placeholder="Adresse Mail" class="text-input" required>
            <input type="text" name="pseudoname" id="pseudo" placeholder="Pseudonyme" class="text-input" required>
            <div class="pass-input">
                <input type="password" name="password" id="password" placeholder="Mot de Passe" class="text-input" required>
                <i class="bi bi-eye-fill" id="pass-show"></i>
            </div>
            <input type="password" name="pass-check" id="pass-check" placeholder="Confirmer Mot de Passe" class="text-input" required>
            <input type="submit" value="Inscription" class="button">
        </form>
        <h4>Vous avez déjà un compte ?</h4>
        <a href="./" class="button">Se connecter</a>
    </main>
    <script src="js/main.js"></script>
</body>
</html>