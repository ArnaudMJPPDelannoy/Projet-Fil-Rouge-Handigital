<?php
$friendId = $friend->getId();
// Retreive the last message send by this friend, truncate it and display it.
$lastMsg = "Aucun.";

require "scripts/connect.php";

$messageRepo = new MessagesRepository($pdo);

$messages = $messageRepo->getConversation((int) $_SESSION["user"], $friendId);
if (count($messages) > 0) {
    $messages = array_filter($messages, function($message) use ($friendId) {
        return $message->getSenderId() == $friendId;
    });

    usort($messages, function(Message $a, Message $b) {
        if ($a->getSendDateTime() < $b->getSendDateTime()) {
            return -1;
        } else {
            return 1;
        }
    });

    $lastMsg = $messages[count($messages) - 1]->getContent();

    if (strlen($lastMsg) > 25) {
        $lastMsg = substr($lastMsg, 0, 25);
        $lastMsg = $lastMsg . "...";
    }
}
?>

<a href="friendMessage.php?user_id=<?php echo $friendId; ?>&previous_url=feed.php?category=<?php echo $category; ?>" class="game_card_link">
    <article class="card game_card">
        <img src="<?php echo $friend->getProfileImageUrl(); ?>" alt="Icône de l'utilisateur.">
        <div>
            <h2><?php echo $friend->getUsername(); ?></h2>
            <p>Dernier Message :<br><?php echo $lastMsg; ?></p>
        </div>
    </article>
</a>