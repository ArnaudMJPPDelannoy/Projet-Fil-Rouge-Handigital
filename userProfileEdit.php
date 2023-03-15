<?php
require "scripts/functions.php";
require "scripts/connect.php";
require "scripts/checkConnect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get((int) $_SESSION["user"]);
$previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "userProfile.php";

if (isSetAndNotEmptyObject($_POST, "lastname") && isSetAndNotEmptyObject($_POST, "firstname") && isSetAndNotEmptyObject($_POST, "age") && isSetAndNotEmptyObject($_POST, "gender") && isSetAndNotEmptyObject($_POST, "email") && isSetAndNotEmptyObject($_POST, "pseudoname")) {
    $user->setLastname(strip_tags($_POST["lastname"]));
    $user->setFirstname(strip_tags($_POST["firstname"]));
    $user->setAge(strip_tags($_POST["age"]));
    $user->setGender(strip_tags($_POST["gender"]));
    $user->setEmail(strip_tags($_POST["email"]));
    $user->setUsername(strip_tags($_POST["pseudoname"]));
    $newProfileImageUrl = "";

    if (isSetAndNotEmptyObject($_FILES, "profile_image") && $_FILES["profile_image"]["error"] == 0) {
        $image = $_FILES["profile_image"];
        if ($image["size"] <= 500000) {
            $fileInfo = pathinfo($image["name"]);
            $uploadExtension = $fileInfo["extension"];
            $allowedExtensions = array("jpg", "jpeg", "webp", "png");

            if (in_array($uploadExtension, $allowedExtensions)) {
                $filename = basename($image["name"]);
                $newProfileImageUrl = date("d-m-Y-H-i-s") . "-" . strip_tags($filename);
                $success = move_uploaded_file($image["tmp_name"], "img/" . $newProfileImageUrl);
                if ($success) {
                    $user->setProfileImageUrl($newProfileImageUrl);
                } else {
                    $error = "Le fichier n'a pas pu être transféré pour une raison inconnue.";
                }
            } else {
                $error = "Extension du fichier invalide.";
            }
        } else {
            $error = "Image trop volumineuse.";
        }
    } else if (isSetAndNotEmptyObject($_FILES, "profile_image") && !empty($_FILES["banner"]["name"]) && $_FILES["profile_image"]["error"] != 0) {
        $error = "Une erreur s'est produite lors du téléversement du fichier.";
    }

    if (!isset($error) || empty($error)) {
        $userRepo->update($user);
        header("Location:" . $previousUrl);
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
    <title>Modification du Profil Utilisateur</title>
</head>
<body>
    <main>
        <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <h1>Modification du Profil Utilisateur</h1>
        <p class="error"><?php if (isset($error) && !empty($error)) echo $error; ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" id="lastname" placeholder="Nom" class="text-input" value="<?php echo $user->getLastname(); ?>" required>
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" id="firstname" placeholder="Prénom" class="text-input" value="<?php echo $user->getFirstname(); ?>" required>
            <label for="age">Age :</label>
            <input type="number" name="age" id="age" min="0" placeholder="Age" class="text-input" value="<?php echo $user->getAge(); ?>" required>
            <label for="man">Genre :</label><br>
            <input type="radio" name="gender" id="man" value="man" <?php if ($user->getGender() == "man") echo "checked"; ?> required> <label for="man">Homme</label><br>
            <input type="radio" name="gender" id="woman" value="woman" <?php if ($user->getGender() == "woman") echo "checked"; ?>> <label for="woman">Femme</label><br>
            <input type="radio" name="gender" id="other" value="other" <?php if ($user->getGender() == "other") echo "checked"; ?>> <label for="other">Autre</label><br>
            <label for="email">E-Mail :</label>
            <input type="email" name="email" id="email" placeholder="Adresse Mail" class="text-input" value="<?php echo $user->getEmail(); ?>" required>
            <label for="pseudo">Pseudonyme :</label>
            <input type="text" name="pseudoname" id="pseudo" placeholder="Pseudonyme" class="text-input" value="<?php echo $user->getUsername(); ?>" required>
            <label for="profile_image">Image de Profil :<br><img src="<?php echo $user->getProfileImageUrl(); ?>" alt="Image de Profil" class="profile_edit_image"></label>
            <input type="file" name="profile_image" id="profile_image" class="profile_image_input">
            <input type="submit" class="button" value="Modifier Profil">
        </form>
        <br>
        <a class="button" href="passChange.php?previous_url=userProfileEdit.php?previous_url=<?php echo $previousUrl; ?>">Changer le Mot de Passe</a>
    </main>
</body>
</html>