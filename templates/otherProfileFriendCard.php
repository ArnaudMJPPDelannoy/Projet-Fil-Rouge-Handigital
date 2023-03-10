<?php
$friendId = $friend->getId();
$status = "Status To Be Added.";
?>

<a href="otherProfile.php?user_id=<?php echo $friendId; ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $friend->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $friend->getUsername(); ?></h2>
            <p><?php echo $status; ?></p>
        </div>
        <div class="card_buttons">
            <?php if (!$userRepo->isFriend($_SESSION["user"], $friendId)) { ?>
                <a href="otherProfile.php?user_id=<?php echo $user->getId(); ?>&add_friend=<?php echo $friendId; ?>&previous_url=<?php echo $previousUrl; ?>" class="fav-heart"><i class="bi bi-heart"></i></a>
            <?php } ?>
            <a href="#ToMessagePage" class="msg-bubble"><i class="bi bi-chat-dots"></i></a>
        </div>
    </article>
</a>