<?php
require "scripts/functions.php";
if (isSetAndNotEmptyObject($_POST, "lastname") && isSetAndNotEmptyObject($_POST, "firstname") && isSetAndNotEmptyObject($_POST, "age") && isSetAndNotEmptyObject($_POST, "gender") && isSetAndNotEmptyObject($_POST, "email") && isSetAndNotEmptyObject($_POST, "pseudoname") && isSetAndNotEmptyObject($_POST, "password") && isSetAndNotEmptyObject($_POST, "pass-check")) {
    $password = strip_tags($_POST["password"]);
    $passCheck = strip_tags($_POST["pass-check"]);
    if ($password != $passCheck) {
        $error = "Les mots de passes doivent être identiques.";
    } else {
        require "scripts/connect.php";
        $lastname = strip_tags($_POST["lastname"]);
        $firstname = strip_tags($_POST["firstname"]);
        $age = strip_tags($_POST["age"]);
        $gender = strip_tags($_POST["gender"]);
        $email = strip_tags($_POST["email"]);
        $username = strip_tags($_POST["pseudoname"]);
        $truePassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $pdo->prepare("INSERT INTO `users` (lastname, firstname, age, gender, email, username, password) VALUES (:lname, :fname, :age, :gender, :email, :uname, :pass)");
        $query->bindValue(":lname", $lastname);
        $query->bindValue(":fname", $firstname);
        $query->bindValue(":age", $age);
        $query->bindValue(":gender", $gender);
        $query->bindValue(":email", $email);
        $query->bindValue(":uname", $username);
        $query->bindValue(":pass", $truePassword);
        $query->execute();
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