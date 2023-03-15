<?php
require "scripts/functions.php";
require "scripts/connect.php";
$previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "./";

$passwordResetSuccess = false;

if (isSetAndNotEmptyObject($_POST, "username")) {
    $userRepo = new UsersRepository($pdo);
    $username = strip_tags($_POST["username"]);
    if ($userRepo->exists($username)) {
        $user = $userRepo->get($username);
        $newPassword = randomPass();
        
        $mailTo = $user->getEmail();
        $mailSubject = "Nouveau Mot de Passe";
        $mailMessage = "Voici votre nouveau Mot de Passe : " . $newPassword . "\nN'oubliez pas de le changer !";

        $mailSent = mail($mailTo, $mailSubject, $mailMessage);

        if ($mailSent) {
            $passHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $user->setPassword($passHash);
            $userRepo->update($user);
            $passwordResetSuccess = true;
        } else {
            $error = "Une erreur s'est produite lors de l'envoi du mail. Votre Mot de Passe n'a pas pu être réinitialisé.";
        }

    } else {
        $error = "Aucun utilisateur avec ce Pseudonyme n'a été trouvé.";
    }
}

function randomPass()
{
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $max = strlen($alphabet) - 1;
    $genPass = "";

    for ($i = 0; $i < 10; $i++) {
        $char = $alphabet[random_int(0, $max)];
        $genPass = $genPass . $char;
    }

    return $genPass;
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
    <title>Réinitialisation du Mot de Passe</title>
</head>
<body>
    <?php if ($passwordResetSuccess) { ?>
        <main>
            <h1>Mot de Passe réinitialisé !</h1>
            <h3>Un E-Mail vous a été envoyé contenant votre nouveau mot de passe.</h3>
            <p>Nous vous conseillons de changer votre mot de passe le plus vite possible dans les parametres du compte.</p>
            <a href="./" class="button">Retourner à la page de connexion</a>
        </main>
        <script src="js/main.js"></script>
    <?php } else { ?>
        <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <main>
            <h1>Réinitialisation du Mot de Passe</h1>
            <h3>ATTENTION : CETTE ACTION EST IRRÉVERSIBLE</h3>
            <p class="error"><?php if (isset($error) && !empty($error)) echo $error; ?></p>
            <form action="" method="post">
                <input type="text" class="text-input" name="username" id="username" placeholder="Nom d'utilisateur" required>
                <input type="submit" class="button" value="Réinitialiser le Mot de Passe">
            </form>
        </main>
    <?php } ?>
</body>
</html>