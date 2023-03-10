<?php
$playerId = $player->getId();
?>

<a href="otherProfile.php?user_id=<?php echo $playerId; ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $player->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $player->getUsername(); ?></h2>
            <p>Jeu en commun :<br><?php echo $game->getName(); ?></p>
        </div>
        <div class="card_buttons">
            <a href="feed.php?category=friends&add_friend=<?php echo $playerId; ?>" class="fav-heart"><i class="bi bi-heart"></i></a>
            <a href="#ToMessagePage" class="msg-bubble"><i class="bi bi-chat-dots"></i></a>
        </div>
    </article>
</a>