<?php
$userId = $user->getId();
$status = "Status To Be Added.";
?>

<a href="otherProfile.php?user_id=<?php echo $userId ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $user->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $user->getUsername(); ?></h2>
            <p><?php echo $status; ?></p>
        </div>
        <div class="card_buttons">
            <?php if (!$userRepo->isFriend($_SESSION["user"], $userId)) { ?>
                <a href="gamePage.php?add_friend=<?php echo $userId; ?>&game_id=<?php echo $gameId; ?>&previous_url=<?php echo $previousUrl; ?>" class="fav-heart"><i class="bi bi-heart"></i></a>
            <?php } ?>
            <a href="#ToMessagePage" class="msg-bubble"><i class="bi bi-chat-dots"></i></a>
        </div>
    </article>
</a>