<?php
if (isset($user)) {
    $userId = $user->getId();
    $linkUrl = "otherProfile.php?user_id=". $userId . "&previous_url=friendMessage.php?user_id=" . $userId. "%26previous_url=" . $previousUrl;
    $linkText = "Voir le profil de cet ami";
} else if (isset($game)) {
    $gameId = $game->getId();
    $linkUrl = "gamePage.php?game_id=" . $gameId . "&previous_url=forumPage.php?game_id=" . $gameId . "%26previous_url=" . $previousUrl;
    $linkText = "Voir la page de ce jeu";
}
?>

<header class="message_header">
    <a href="<?php echo $previousUrl; ?>"><i class="bi bi-arrow-left"></i></a>
    <h1><?php echo $headerName; ?></h1>
    <a href="#TBA" id="message_option_dots"><i class="bi bi-three-dots-vertical"></i></a>
</header>
<aside class="message_options" id="message_options">
    <a href="<?php echo $linkUrl; ?>"><?php echo $linkText; ?></a>
</aside>