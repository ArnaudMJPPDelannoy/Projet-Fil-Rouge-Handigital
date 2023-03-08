<?php
$gameId = $game->getId();
$description = $game->getDescription();
if (strlen($description) > 75) {
    $description = substr($description, 0, 75);
    $description = $description . "...";
}
?>

<a href="gamePage.php?game_id=<?php echo $gameId; ?>" class="game_card_link">
    <article class="card game_card" id="game_card_<?php echo $articleId; ?>">
        <img src="<?php echo $game->getIconImageUrl(); ?>" alt="Icône du Jeu.">
        <div>
            <h2><?php echo $game->getName(); ?></h2>
            <p><?php echo $description; ?></p>
        </div>
    </article>
</a>