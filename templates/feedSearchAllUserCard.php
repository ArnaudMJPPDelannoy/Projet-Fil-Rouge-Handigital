<?php
require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$userId = $user->getId();
$status = $user->getConnected() ? "Connecté" : "Déconnecté";
if ($user->getGender() == "woman") {
    $status = $status . "e";
} else if ($user->getGender() == "other") {
    $status = $status . "(e)";
}
?>

<a href="otherProfile.php?user_id=<?php echo $userId ?>&previous_url=feed.php?category=friends" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $user->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $user->getUsername(); ?></h2>
            <p><?php echo $status; ?></p>
        </div>
        <div class="card_buttons">
            <?php if (!$userRepo->isFriend($_SESSION["user"], $userId)) { ?>
                <a href="feed.php?add_friend=<?php echo $userId; ?>&category=friends" class="fav-heart"><i class="bi bi-heart"></i></a>
            <?php } ?>
            <a href="friendMessage.php?user_id=<?php echo $userId; ?>&previous_url=feed.php?category=friends" class="msg-bubble"><i class="bi bi-chat-dots"></i></a>
        </div>
    </article>
</a>