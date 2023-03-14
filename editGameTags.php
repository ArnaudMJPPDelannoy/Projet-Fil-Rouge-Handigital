<?php
require "scripts/functions.php";

if (isSetAndNotEmptyObject($_GET, "game_id")) {
    require "scripts/connect.php";
    $repository = new GamesRepository($pdo);
    $game = $repository->get((int) $_GET["game_id"]);

    if (!isset($game) || empty($game)) {
        header("Location:feed.php");
    } else {
        if (isSetAndNotEmptyObject($_GET, "previous_url")) {
            $previousUrl = $_GET["previous_url"];
        } else {
            $previousUrl = "gamePage.php?game_id=" . $game->getId();
        }

        $userRepo = new UsersRepository($pdo);
        $curUser = $userRepo->get((int) $_SESSION["user"]);

        $gameId = $game->getId();
        $gameName = $game->getName();
        $gameBanner = $game->getBannerImageUrl();
        $gameIcon = $game->getIconImageUrl();
        $gameGenres = $repository->getGenres($gameId);
        $gameGenreNames = array_map(function($genre) {
            return $genre->getName();
        }, $gameGenres);

        if (isSetAndNotEmptyObject($_POST, "new_tags")) {
            $gameGenreRepo = new GameGenresRepository($pdo);
            $gameTagsString = strip_tags($_POST["new_tags"]);
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

            $repository->deleteAllGenres($game);

            foreach ($gameTags as $tag) {
                if (!$gameGenreRepo->exists($tag)) {
                    $newTag = new GameGenre(["name" => $tag]);
                    $gameGenreRepo->add($newTag);
                } else {
                    $newTag = $gameGenreRepo->get($tag);
                }
                $repository->addLinkToGenre($game, $newTag);
            }

            header("Location:" . $previousUrl);
        }
    }
} else {
    header("Location:feed.php");
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
    <title><?php echo $gameName ?></title>
</head>
<body>
    <main class="main_game_page">
        <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <div class="banner">
            <img src="<?php echo $gameBanner; ?>" alt="Bannière du Jeu">
            <img class="game_icon" src="<?php echo $gameIcon; ?>" alt="Icône du Jeu">
        </div>
        <h1><?php echo $gameName; ?></h1>
        <section class="content_game_page">
            <form action="" method="post">
                <input class="text-input" type="text" name="new_tags" id="new_tags" value="<?php echo implode(", ", $gameGenreNames); ?>">
                <input type="submit" value="Modifier" class="button">
            </form>
        </section>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>