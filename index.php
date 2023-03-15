<?php
require "scripts/functions.php";
if (isSetAndNotEmptyObject($_POST, "username") && isSetAndNotEmptyObject($_POST, "password")) {
    require "scripts/connect.php";
    $userRepo = new UsersRepository($pdo);
    $username = $_POST["username"];
    $password = strip_tags($_POST["password"]);
    $user = $userRepo->get($username);
    if (isset($user) && !empty($user) && password_verify($password, $user->getPassword())) {
        $_SESSION["user"] = $user->getId();
        $userRepo->connectUser($user);
    } else {
        $error = "Identifiant ou Mot de Passe invalide.";
    }
}

if (isSetAndNotEmptyObject($_SESSION, "user")) {
    header("Location:feed.php");
    die;
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
    <title>Connexion</title>
</head>
<body>
    <main>
        <h1>Connexion</h1>
        <h3 class="error"><?php if (isset($error) && !empty("error")) echo $error; ?></h3>
        <form action="" method="post">
            <input type="text" name="username" id="username" placeholder="Identifiant" class="text-input" required>
            <div class="pass-input">
                <input type="password" name="password" id="password" placeholder="Mot de Passe" class="text-input" required>
                <i class="bi bi-eye-fill" id="pass-show"></i>
            </div>
            <input type="submit" value="Se connecter" class="button">
        </form>
        <a href="#">Mot de passe oublié ?</a>
        <h3>Pas encore inscrit ?</h3>
        <a href="signup.php" class="button">Inscription</a>
    </main>
    <script src="js/main.js"></script>
</body>
</html>