<?php
require "scripts/functions.php";
require "scripts/connect.php";
if (isset($_GET["category"])) {
    $category = $_GET["category"];
} else {
    $category = "news";
}

switch ($category) {
    case "news":
        $title = "News";
        $repository = new ArticlesRepository($pdo);
        $content = $repository->getAll();
        break;
    case "games":
        $title = "Jeux Préférés";
        $repository = new UsersRepository($pdo);
        $content = $repository->getPlayedGames($_SESSION["user"]);
        break;
    case "game_list":
        $title = "Liste des Jeux";
        $repository = new GamesRepository($pdo);
        $content = $repository->getAll();
        break;
    case "friends":
        $title = "Liste d'Amis";
        $repository = new UsersRepository($pdo);
        $content = $repository->getFriends($_SESSION["user"]);
        $previousUrl = "feed.php?category=friends";
        break;
    case "chat-friends":
        $title = "Messages";
        $userRepo = new UsersRepository($pdo);
        $content = $userRepo->getFriends($_SESSION["user"]);
        $previousUrl = "feed.php?category=chat-friends";
        break;
    case "chat-forum":
        $title = "Messages";
        $userRepo = new UsersRepository($pdo);
        $content = $userRepo->getPlayedGames($_SESSION["user"]);
        break;
    default:
        $category = "news";
        $title = "News";
        break;
}

if (substr($category, 0, 4) == "chat") {
    $indicatorClass = "pos-chat";
} else {
    $indicatorClass = "pos-" . $category;
}

if (isSetAndNotEmptyObject($_GET, "search")) {
    $searchStr = strip_tags($_GET["search"]);
    if (isSetAndNotEmptyObject($_GET, "restrict")) {
        switch ($category) {
            case "news":
                $searchTitle = isSetAndNotEmptyObject($_GET, "search_title") ? strip_tags($_GET["search_title"]) : false;
                $searchContent = isSetAndNotEmptyObject($_GET, "search_content") ? strip_tags($_GET["search_content"]) : false;
                $content = $repository->search($searchStr, $searchTitle, $searchContent);
                break;
            case "games":
                $searchName = isSetAndNotEmptyObject($_GET, "search_name") ? strip_tags($_GET["search_name"]) : false;
                $searchDesc = isSetAndNotEmptyObject($_GET, "search_desc") ? strip_tags($_GET["search_desc"]) : false;
                $content = $repository->searchPlayedGames($_SESSION["user"], $searchStr, $searchName, $searchDesc);
                break;
            case "game_list":
                $searchName = isSetAndNotEmptyObject($_GET, "search_name") ? strip_tags($_GET["search_name"]) : false;
                $searchDesc = isSetAndNotEmptyObject($_GET, "search_desc") ? strip_tags($_GET["search_desc"]) : false;
                $content = $repository->search($searchStr, $searchName, $searchDesc);
                break;
            case "friends":
            case "chat-friends":
                $userRepo = new UsersRepository($pdo);
                $content = $userRepo->searchFriends($_SESSION["user"], $searchStr);
                break;
            case "chat-forum":
                $messageRepo = new ForumMsgRepository($pdo);
                $searchGameName = isSetAndNotEmptyObject($_GET, "search_game_name") ? strip_tags($_GET["search_game_name"]) : false;
                $searchPosterName = isSetAndNotEmptyObject($_GET, "search_poster_name") ? strip_tags($_GET["search_poster_name"]) : false;
                $content = $messageRepo->search($_SESSION["user"], $searchStr, $searchGameName, $searchPosterName);
                break;
        }
    }
}

if (isSetAndNotEmptyObject($_GET, "add_friend")) {
    $repository->addFriend($_SESSION["user"], $_GET["add_friend"]);
    header("Location:feed.php?category=" . $category);
}

function displayContent($category, $content)
{
    switch ($category) {
        case "news":
            foreach ($content as $article) {
                require "templates/articleCard.php";
            }
            break;
        case "games":
            foreach ($content as $game) {
                require "templates/gameCard.php";
            }
            displayAddGameButton();
            break;
        case "game_list":
            foreach ($content as $game) {
                require "templates/gameCard.php";
            }
            break;
        case "friends":
            foreach ($content as $friend) {
                require "templates/friendCard.php";
            }
            displayFriendSuggestions();
            break;
        case "chat-friends":
            foreach ($content as $friend) {
                require "templates/friendCard.php";
            }
            break;
        case "chat-forum":
            foreach ($content as $game) {
                require "templates/gameForumCard.php";
            }
            break;
    }
}

function displayNoContentMsg($category) {
    if (isSetAndNotEmptyObject($_GET, "search")) {
        echo "<h3>Aucun élément ne correspond à votre recherche.</h3>";
        return;
    }
    switch ($category) {
        case "news":
            echo "<h3>Il n'y a pas d'article à afficher.</h3>";
            break;
        case "games":
            echo "<h3>Vous n'avez pas encore ajouté de jeu. À quoi jouez vous ?</h3>";
            displayAddGameButton();
            break;
        case "friends":
            echo "<h3>Vous n'avez pas encore d'ami. Recherchez quelqu'un ou trouvez-en dans les suggestions ci-dessous !</h3>";
            displayFriendSuggestions();
            break;
        case "chat-friends":
            echo "<h3>Vous n'avez pas encore d'ami. Allez dans l'onglet Amis et trouvez en !</h3>";
            break;
        case "chat-forum":
            echo "<h3>Vous n'avez pas encore ajouté de jeu. Rendez-vous dans l'onglet Jeux et choisissez vos préférés !</h3>";
            break;
        default:
            echo "<h3>Il n'y a rien à afficher.</h3>";
            break;
    }
}

function displayFriendSuggestions()
{ ?>
    <h2>Suggestions</h2>
    <p>Ces personnes jouent aux mêmes jeux ou types de jeux que vous.</p>
<?php
    require "scripts/connect.php";
    $userRepo = new UsersRepository($pdo);
    $gameRepo = new GamesRepository($pdo);
    $suggestedPlayers = [];
    $playedGames = $userRepo->getPlayedGames($_SESSION["user"]);
    
    foreach ($playedGames as $game) {
        $gamePlayers = $gameRepo->getPlayers($game->getId());
        foreach ($gamePlayers as $player) {
            if ($player->getId() != $_SESSION["user"] && !in_array($player->getId(), $suggestedPlayers)) {
                $userFriends = $userRepo->getFriends($_SESSION["user"]);
                $alreadyFriends = false;
                foreach ($userFriends as $friend) {
                    if ($friend->getId() == $player->getId()) {
                        $alreadyFriends = true;
                    }
                }
                if (!$alreadyFriends) {
                    require "templates/friendSuggestionCard.php";
                    $suggestedPlayers[] = $player->getId();
                }
            }
        }
    }

    if (count($suggestedPlayers) <= 0) { ?>
        <p>Nous n'avons pas pu trouver de joueur correspondant à vos critères de jeux.<br>Essayez d'ajouter des jeux à vos favoris !</p>
    <?php }
}

function displayAddGameButton()
{ ?>
    <a href="?category=game_list" class="button">Parcourir les jeux</a>
<?php }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title ?></title>
</head>
<body>
    <?php require "include/header.php"; ?>
    <main class="content" id="content">
        <?php
            if (!isset($content) || $content === false) { ?>
                <h3>Une erreur c'est produite lors de la récuperation du contenu.</h3>
            <?php } else if (empty($content)) {
                displayNoContentMsg($category);
            } else {
                displayContent($category, $content);
            }
        ?>
    </main>
    <?php require "include/footer.php"; ?>
    <script src="js/feed.js"></script>
</body>
</html>