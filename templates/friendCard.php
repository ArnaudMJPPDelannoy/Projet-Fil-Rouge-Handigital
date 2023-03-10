<?php
$friendId = $friend->getId();
// Retreive the last message send by this friend, truncate it and display it.
$lastMsg = "Aucun.";
?>

<a href="otherProfile.php?user_id=<?php echo $friendId; ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $friend->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $friend->getUsername(); ?></h2>
            <p>Dernier Message :<br><?php echo $lastMsg; ?></p>
        </div>
    </article>
</a>