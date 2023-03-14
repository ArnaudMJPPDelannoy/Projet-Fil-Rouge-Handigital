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
            $previousUrl = "feed.php?category=games";
        }

        $userRepo = new UsersRepository($pdo);
        $curUser = $userRepo->get((int) $_SESSION["user"]);

        if (isSetAndNotEmptyObject($_GET, "add_friend")) {
            $userRepo->addFriend($_SESSION["user"], $_GET["add_friend"]);
            header("Location:gamePage.php?game_id=" . $_GET["game_id"] . "&previous_url=" . $previousUrl);
        }
        

        $gameId = $game->getId();
        $gameName = $game->getName();
        $gameDescription = $game->getDescription();
        $gameBanner = $game->getBannerImageUrl();
        $gameIcon = $game->getIconImageUrl();
        $gameGenres = $repository->getGenres($gameId);
        $gamePlayers = $repository->getPlayers($gameId);

        if (isSetAndNotEmptyObject($_GET, "game_faved")) {
            $gameFaved = $_GET["game_faved"];
            if ($gameFaved == "true") {
                $alreadyFaved = false;
                foreach ($gamePlayers as $user) {
                    if ($user->getId() == $_SESSION["user"]) {
                        $alreadyFaved = true;
                    }
                }
                if (!$alreadyFaved) $userRepo->addPlayedGame($_SESSION["user"], $gameId);
            } else {
                $userRepo->deletePlayedGame($_SESSION["user"], $gameId);
            }
        }

        $gameFaved = false;
        $userGames = $userRepo->getPlayedGames($_SESSION["user"]);
        foreach ($userGames as $game) {
            if ($game->getId() == $gameId) {
                $gameFaved = true;
            }
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
        <a href="gamePage.php?game_id=<?php echo $gameId ?>&game_faved=<?php echo $gameFaved == true ? "false" : "true"; ?>&previous_url=<?php echo prepareForUrl($previousUrl); ?>" class="game_favicon"><i class="bi bi-heart<?php if ($gameFaved) echo "break";?>"></i></a>
        <h1><?php echo $gameName; ?></h1>
        <section class="content_game_page">
            <p class="article-content"><?php echo $gameDescription; ?></p>
            <p>Tags</p>
            <div class="game_tag_container">
                <?php
                    if (count($gameGenres) > 0) {
                        foreach ($gameGenres as $genre) { ?>
                            <p class="game_tag"><?php echo $genre->getName(); ?></p>
                        <?php }
                    } else { ?>
                        <p>Il n'y a pas de tag sur ce jeu.</p>
                    <?php }
                ?>
            </div>
            <?php if ($curUser->getRole() == "admin") { ?>
                <a href="editGameTags.php?game_id=<?php echo $gameId; ?>&previous_url=gamePage.php?game_id=<?php echo $gameId; ?>%26previous_url=<?php echo $previousUrl; ?>">Modifier les Tags</a>
            <?php } ?>
            <h3>Personnes qui aiment ce jeu :</h3>
            <?php
                if (count($gamePlayers) > 0) {
                    foreach ($gamePlayers as $user) {
                        if ($user->getId() != $_SESSION["user"]) require "templates/gamePageUserCard.php";
                    }
                } else { ?>
                    <p>Personne n'a ce jeu dans ses favoris.<br>Parlez-en à vos amis !</p>
                <?php }
            ?>
            <a href="forumPage.php?game_id=<?php echo $gameId; ?>&previous_url=gamePage.php?game_id=<?php echo $gameId; ?>%26previous_url=<?php echo prepareForUrl($previousUrl); ?>" class="button">Accéder au Forum</a>
        </section>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>