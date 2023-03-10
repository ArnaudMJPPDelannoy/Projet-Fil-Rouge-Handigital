<?php
$gameId = $game->getId();

// Get last message and it's sender and display them.
$lastUserName = "Aucun";
$lastMessage = "Message";

require "scripts/connect.php";

$userRepo = new UsersRepository($pdo);
$forumMgsRepo = new ForumMsgRepository($pdo);

$messages = $forumMgsRepo->getConversation($gameId);
if (count($messages) > 0) {
    usort($messages, function(ForumMsg $a, ForumMsg $b) {
        if ($a->getSendDateTime() < $b->getSendDateTime()) {
            return 1;
        } else {
            return -1;
        }
    });

    $lastMessageObject = $messages[0];

    $lastUser = $userRepo->get($lastMessageObject->getForumPosterId());
    $lastUserName = $lastUser->getUsername();
    $lastMessage = $lastMessageObject->getContent();

    if (strlen($lastMessage) > 25) {
        $lastMessage = substr($lastMessage, 0, 25);
        $lastMessage = $lastMessage . "...";
    }
}
?>

<a href="forumPage.php?game_id=<?php echo $gameId; ?>&previous_url=feed.php?category=<?php echo $category; ?>" class="game_card_link">
    <article class="card game_card" id="game_card_<?php echo $gameId; ?>">
        <img src="<?php echo $game->getIconImageUrl(); ?>" alt="Icône du Jeu.">
        <div>
            <h2><?php echo $game->getName(); ?></h2>
            <p><?php echo $lastUserName; ?></p>
            <p><?php echo $lastMessage; ?></p>
        </div>
    </article>
</a>