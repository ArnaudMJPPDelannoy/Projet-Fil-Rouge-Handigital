<?php
require "scripts/functions.php";
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get($_SESSION["user"]);
$previousUrl = $_GET["previous_url"];

if (isSetAndNotEmptyObject($_GET, "disconnect")) {
    unset($_SESSION["user"]);
    header("Location:index.php");
}

function displayFavGames(UsersRepository $userRepo)
{
    $favGames = $userRepo->getPlayedGames($_SESSION["user"]);
    foreach ($favGames as $game) { ?>
        <a href="gamePage.php?game_id=<?php echo $game->getId(); ?>&previous_url=userProfile.php"><img class="profile_page_game_icon" src="<?php echo $game->getIconImageUrl(); ?>" alt="Icône du Jeu"></a>
    <?php }
}

function displayProfileFriends(UsersRepository $userRepo)
{
    $friendList = $userRepo->getFriends($_SESSION["user"]);
    if (count($friendList) > 0) {
        foreach ($friendList as $friend) {
            require "templates/userProfileFriendCard.php";
        }
    } else { ?>
        <p>Vous n'avez pas encore d'ami.<br>Allez dans l'onglet "Liste d'amis" et regardez les suggestions !</p>
    <?php }
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
    <title>Profil Utilisateur</title>
</head>
<body>
    <a href="<?php echo $previousUrl; ?>" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
    <main class="content_user_profile">
        <img class="profile_page_image" src="<?php echo $user->getProfileImageUrl(); ?>" alt="Image de Profil de l'Utilisateur">
        <h1><?php echo $user->getUsername(); ?></h1>
        <a href="#TBA" class="button">Modifier le Profil</a>
        <h2>Jeux préférés :</h2>
        <div class="fav_games_profile">
            <?php displayFavGames($userRepo); ?>
        </div>
        <h2>Amis :</h2>
        <div class="user_profile_friends">
            <?php displayProfileFriends($userRepo); ?>
        </div>
        <a href="userProfile.php?disconnect=true" class="button">Se déconnecter</a>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>