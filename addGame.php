<?php
    require "scripts/functions.php";
    require "scripts/connect.php";

    $previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "feed.php?category=game_list";

    $gameGenreRepo = new GameGenresRepository($pdo);

    $gameRepo = new GamesRepository($pdo);
    $game = new Game([]);

    if (isSetAndNotEmptyObject($_POST, "game_name") && isSetAndNotEmptyObject($_POST, "game_desc") && isSetAndNotEmptyObject($_POST, "game_tags") && isSetAndNotEmptyObject($_FILES, "game_banner") && isSetAndNotEmptyObject($_FILES, "game_icon")) {
        $game->setName(strip_tags($_POST["game_name"]));
        $game->setDescription(strip_tags($_POST["game_desc"]));
        $gameTagsString = strip_tags($_POST["game_tags"]);
        $newBannerImageUrl = "";
        $newIconImageUrl = "";
        
        $gameTags = [];
        if (strpos($gameTagsString, ",") === false) {
            $gameTags[] = $gameTagsString;
        } else {
            while (strpos($gameTagsString, ",") !== false) {
                $pos = strpos($gameTagsString, ",");
                $gameTags[] = trim(substr($gameTagsString, 0, $pos));
                $gameTagsString = substr($gameTagsString, $pos + 1);
            }
            if (!empty($gameTagsString)) {
                $gameTags[] = trim($gameTagsString);
            }
        }
        $gameTags = array_filter($gameTags, function($elem) {
            return !empty($elem);
        });

        foreach ($gameTags as $tag) {
            if (!$gameGenreRepo->exists($tag)) {
                $newTag = new GameGenre(["name" => $tag]);
                $gameGenreRepo->add($newTag);
            }
        }

        if (isSetAndNotEmptyObject($_FILES, "game_banner") && $_FILES["game_banner"]["error"] == 0) {
            $image = $_FILES["game_banner"];
            if ($image["size"] <= 500000) {
                $fileInfo = pathinfo($image["name"]);
                $uploadExtension = $fileInfo["extension"];
                $allowedExtensions = array("jpg", "jpeg", "webp", "png");
    
                if (in_array($uploadExtension, $allowedExtensions)) {
                    $filename = basename($image["name"]);
                    $newBannerImageUrl = date("d-m-Y-H-i-s") . "-" . strip_tags($filename);
                    $success = move_uploaded_file($image["tmp_name"], "img/" . $newBannerImageUrl);
                    if ($success) {
                        $game->setBannerImageUrl($newBannerImageUrl);
                    } else {
                        $error += "La bannière n'a pas pu être transféré pour une raison inconnue.\n";
                    }
                } else {
                    $error += "Extension du fichier de la bannière invalide.\n";
                }
            } else {
                $error += "Image de la bannière trop volumineuse.\n";
            }
        } else if (isSetAndNotEmptyObject($_FILES, "game_banner") && !empty($_FILES["game_banner"]["name"]) && $_FILES["banner"]["error"] != 0) {
            $error += "Une erreur s'est produite lors du téléversement du fichier de la bannière.\n";
        }

        if (isSetAndNotEmptyObject($_FILES, "game_icon") && $_FILES["game_icon"]["error"] == 0) {
            $image = $_FILES["game_icon"];
            if ($image["size"] <= 500000) {
                $fileInfo = pathinfo($image["name"]);
                $uploadExtension = $fileInfo["extension"];
                $allowedExtensions = array("jpg", "jpeg", "webp", "png");
    
                if (in_array($uploadExtension, $allowedExtensions)) {
                    $filename = basename($image["name"]);
                    $newIconImageUrl = date("d-m-Y-H-i-s") . "-" . strip_tags($filename);
                    $success = move_uploaded_file($image["tmp_name"], "img/" . $newIconImageUrl);
                    if ($success) {
                        $game->setIconImageUrl($newIconImageUrl);
                    } else {
                        $error += "L'icône n'a pas pu être transféré pour une raison inconnue.";
                    }
                } else {
                    $error += "Extension du fichier de l'icône invalide.";
                }
            } else {
                $error += "Image de l'icône trop volumineuse.";
            }
        } else if (isSetAndNotEmptyObject($_FILES, "game_icon") && !empty($_FILES["game_icon"]["name"]) && $_FILES["banner"]["error"] != 0) {
            $error += "Une erreur s'est produite lors du téléversement du fichier de l'icône.";
        }

        if (!isset($error) || empty($error)) {
            $gameRepo->add($game);
            foreach ($gameTags as $tag) {
                $genre = $gameGenreRepo->get($tag);
                $gameRepo->addLinkToGenre($game, $genre);
            }
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
    <title>Ajout d'un Jeu</title>
</head>
<body>
    <main>
        <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <h1>Ajout d'un Jeu</h1>
        <p class="error"><?php if (isset($error) && !empty($error)) echo $error; ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="game_name" id="game_name" class="text-input" placeholder="Nom du Jeu" required >
            <textarea name="game_desc" id="game_desc" cols="30" rows="10" class="textarea-input" placeholder="Courte description du Jeu" required ></textarea>
            <h3>Tags :</h3>
            <p>(Séparés par une virgule)</p>
            <input type="text" name="game_tags" id="game_tags" class="text-input" required >
            <h3>Bannière du Jeu :</h3>
            <p>(Requis)</p>
            <input type="file" name="game_banner" id="game_banner" class="file-input" required />
            <h3>Icône du Jeu :</h3>
            <p>(Requis)</p>
            <input type="file" name="game_icon" id="game_icon" class="file-input" required />
            <input type="submit" value="Ajouter" class="button">
        </form>
    </main>
</body>
</html>