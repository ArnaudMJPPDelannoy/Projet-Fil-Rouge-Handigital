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
    case "friends":
        $title = "Liste d'Amis";
        $repository = new UsersRepository($pdo);
        $content = $repository->getFriends($_SESSION["user"]);
        break;
    case "chat":
        $title = "Messages";
        break;
    default:
        $category = "news";
        $title = "News";
        break;
}
$indicatorClass = "pos-" . $category;

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
                // Require game card template here.
            }
            break;
        case "friends":
            foreach ($content as $friend) {
                // Require friend card template here.
            }
            displayFriendSuggestions();
            break;
        case "chat":
            // Show the secondary Tab-Bar.
            // And figure out the rest.
            break;
    }
}

function displayNoContentMsg($category) {
    switch ($category) {
        case "news":
            echo "<h3>Il n'y a pas d'article à afficher.</h3>";
            break;
        case "games":
            echo "<h3>Vous n'avez pas encore ajouté de jeu. À quoi jouez vous ?</h3>";
            break;
        case "friends":
            echo "<h3>Vous n'avez pas encore d'ami. Recherchez quelqu'un ou trouvez-en dans les suggestions ci-dessous!</h3>";
            displayFriendSuggestions();
            break;
        case "chat":
            // TO DO
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
    // Make the suggestion list and display it here.
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
</body>
</html>