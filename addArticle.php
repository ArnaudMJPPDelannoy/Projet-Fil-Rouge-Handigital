<?php
require "scripts/functions.php";
require "scripts/connect.php";
require "scripts/checkConnect.php";

$previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "feed.php";

$userRepo = new UsersRepository($pdo);
$curUser = $userRepo->get($_SESSION["user"]);

$articleRepo = new ArticlesRepository($pdo);
$article = new Article([]);

if (isSetAndNotEmptyObject($_POST, "title") && isSetAndNotEmptyObject($_POST, "content")) {
    $article->setTitle(strip_tags($_POST["title"]));
    $article->setContent(strip_tags($_POST["content"]));
    $newBannerImageUrl = "";

    if (isSetAndNotEmptyObject($_FILES, "banner") && $_FILES["banner"]["error"] == 0) {
        $image = $_FILES["banner"];
        if ($image["size"] <= 500000) {
            $fileInfo = pathinfo($image["name"]);
            $uploadExtension = $fileInfo["extension"];
            $allowedExtensions = array("jpg", "jpeg", "webp", "png");

            if (in_array($uploadExtension, $allowedExtensions)) {
                $filename = basename($image["name"]);
                $newBannerImageUrl = date("d-m-Y-H-i-s") . "-" . strip_tags($filename);
                $success = move_uploaded_file($image["tmp_name"], "img/" . $newBannerImageUrl);
                if ($success) {
                    $article->setBannerImageUrl($newBannerImageUrl);
                } else {
                    $error = "Le fichier n'a pas pu être transféré pour une raison inconnue.";
                }
            } else {
                $error = "Extension du fichier invalide.";
            }
        } else {
            $error = "Image trop volumineuse.";
        }
    } else if (isSetAndNotEmptyObject($_FILES, "banner") && !empty($_FILES["banner"]["name"]) && $_FILES["banner"]["error"] != 0) {
        $error = "Une erreur s'est produite lors du téléversement du fichier.";
    }

    if (!isset($error) || empty($error)) {
        $articleRepo->add($article);
        $articleRepo->addLinkToWriter($article, $curUser);
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
    <title>Publication d'un Article</title>
</head>
<body>
    <main>
        <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <h1>Publication d'un Article</h1>
        <p class="error"><?php if (isset($error) && !empty($error)) echo $error; ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <input class="text-input" type="text" name="title" id="title" placeholder="Titre de l'Article" required>
            <textarea class="textarea-input" name="content" id="content" cols="30" rows="10" placeholder="Le contenu de l'article" required></textarea>
            <h3>Bannière de l'article :</h3>
            <p>(Non requis)</p>
            <input class="file-input" type="file" name="banner" id="banner">
            <input type="submit" value="Publier" class="button">
        </form>
    </main>
</body>
</html>