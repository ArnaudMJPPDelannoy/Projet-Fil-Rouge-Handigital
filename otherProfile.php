<?php
require "scripts/functions.php";
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$user = $userRepo->get((int) $_GET["user_id"]);
$userId = $user->getId();
$previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "feed.php?category=friends";

if (isSetAndNotEmptyObject($_GET, "add_friend")) {
    $userRepo->addFriend($_SESSION["user"], (int) $_GET["add_friend"]);
    header("Location:otherProfile.php?user_id=" . $userId . "&previous_url=" . $previousUrl);
} else if (isSetAndNotEmptyObject($_GET, "delete_friend")) {
    $userRepo->deleteFriend($_SESSION["user"], (int) $_GET["delete_friend"]);
    header("Location:otherProfile.php?user_id=" . $userId . "&previous_url=" . $previousUrl);
}

function displayFavGames(UsersRepository $userRepo, User $user)
{
    $favGames = $userRepo->getPlayedGames($user->getId());
    if (count($favGames) > 0) {
        foreach ($favGames as $game) { ?>
            <a href="gamePage.php?game_id=<?php echo $game->getId(); ?>&previous_url=userProfile.php"><img class="profile_page_game_icon" src="<?php echo $game->getIconImageUrl(); ?>" alt="Icône du Jeu"></a>
        <?php }
    } else { ?>
        <p>Cet utilisateur n'a pas de jeu favori.</p>
    <?php }
}

function displayProfileFriends(UsersRepository $userRepo, User $user)
{
    $friendList = $userRepo->getFriends($user->getId());
    if (count($friendList) > 0) {
        foreach ($friendList as $friend) {
            if ($friend->getId() != $userRepo->get($_SESSION["user"])->getId()) require "templates/otherProfileFriendCard.php";
        }
    } else { ?>
        <p>Cet utilisateur n'a pas d'ami.<br>Envoyez lui un message !</p>
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
        <div class="other_profile_buttons">
            <?php if ($userRepo->isFriend($_SESSION["user"], $userId)) { ?>
                <a href="otherProfile.php?user_id=<?php echo $userId; ?>&delete_friend=<?php echo $userId; ?>&previous_url=<?php echo $previousUrl; ?>" class="fav-heart-profile"><i class="bi bi-heartbreak"></i></a>
            <?php } else { ?>
                <a href="otherProfile.php?user_id=<?php echo $userId; ?>&add_friend=<?php echo $userId; ?>&previous_url=<?php echo $previousUrl; ?>" class="fav-heart-profile"><i class="bi bi-heart"></i></a>
            <?php } ?>
            <a href="friendMessage.php?user_id=<?php echo $userId; ?>&previous_url=otherProfile.php?user_id=<?php echo $userId; ?>%26previous_url=<?php echo $previousUrl; ?>" class="msg-bubble-profile"><i class="bi bi-chat-dots"></i></a>
        </div>
        <h2>Jeux préférés :</h2>
        <div class="fav_games_profile">
            <?php displayFavGames($userRepo, $user); ?>
        </div>
        <h2>Amis :</h2>
        <div class="user_profile_friends">
            <?php displayProfileFriends($userRepo, $user); ?>
        </div>
        <a href="userProfile.php?disconnect=true" class="button">Se déconnecter</a>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>