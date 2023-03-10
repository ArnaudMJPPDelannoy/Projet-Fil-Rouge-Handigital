<?php
$senderId = $sender->getId();
$content = $message->getContent();

$url = $senderId == $_SESSION["user"] ? "userProfile.php?previous_url=forumPage.php?game_id=" . $game->getId() : "otherProfile.php?user_id=" . $senderId . "&previous_url=forumPage.php?game_id=" . $game->getId();
?>

<a href="<?php echo $url; ?>" class="comment_card_link">
    <article class="card comment_card">
        <img src="<?php echo $sender->getProfileImageUrl(); ?>" alt="Icône du posteur.">
        <div>
            <h2><?php echo $sender->getUsername(); ?></h2>
            <p><?php echo $content; ?></p>
        </div>
    </article>
</a>