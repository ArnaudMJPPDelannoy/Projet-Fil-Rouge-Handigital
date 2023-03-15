<?php
require "scripts/functions.php";
require "scripts/connect.php";
require "scripts/checkConnect.php";
$previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "userProfileEdit.php";
$userRepo = new UsersRepository($pdo);
$curUser = $userRepo->get($_SESSION["user"]);

if (isSetAndNotEmptyObject($_POST, "old_pass") && isSetAndNotEmptyObject($_POST, "new_pass") && isSetAndNotEmptyObject($_POST, "new_pass_confirm")) {
    $oldPassword = strip_tags($_POST["old_pass"]);
    if (!password_verify($oldPassword, $curUser->getPassword())) {
        $error = "L'ancien Mot de Passe n'est pas correct.";
    } else {
        $newPassword = strip_tags($_POST["new_pass"]);
        $newPassConfirm = strip_tags($_POST["new_pass_confirm"]);
        if ($newPassword != $newPassConfirm) {
            $error = "Le nouveau Mot de Passe et sa confirmation ne correspondent pas.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $curUser->setPassword($hashedPassword);
            $userRepo->update($curUser);
            header("Location:" . $previousUrl);
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
    <title>Changement du Mot de Passe</title>
</head>
<body>
    <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
    <main>
        <h1>Changement du Mot de Passe</h1>
        <p class="error"><?php if (isset($error) && !empty($error)) echo $error; ?></p>
        <form action="" method="post">
            <div class="pass-input">
                <input type="password" name="old_pass" id="old_pass" placeholder="Ancien Mot de Passe" class="text-input" required>
                <i class="bi bi-eye-fill pass-show" id="pass-show"></i>
            </div>

            <div class="pass-input">
                <input type="password" name="new_pass" id="new_pass" placeholder="Nouveau Mot de Passe" class="text-input" required>
                <i class="bi bi-eye-fill pass-show" id="pass-show2"></i>
            </div>

            <input type="password" name="new_pass_confirm" id="new_pass_confirm" placeholder="Confirmer Nouveau MDP" class="text-input" required>
            <input type="submit" class="button" value="Changer le Mot de Passe">
        </form>
    </main>
    <script src="js/passChange.js"></script>
</body>
</html>