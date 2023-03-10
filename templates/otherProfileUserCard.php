<?php
$friendId = $friend->getId();
?>

<a href="userProfile.php?previous_url=otherProfile.php?user_id=<?php echo $user->getId(); ?>%26previous_url=<?php echo $previousUrl; ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $friend->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $friend->getUsername(); ?></h2>
        </div>
    </article>
</a>