<?php
require "scripts/functions.php";
if (isSetAndNotEmptyObject($_GET, "game_id")) {
    require "scripts/connect.php";
    $userRepo = new UsersRepository($pdo);
    $gameRepo = new GamesRepository($pdo);
    $messageRepo = new ForumMsgRepository($pdo);
    $game = $gameRepo->get((int) $_GET["game_id"]);
    $headerName = $game->getName();
    $previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "feed.php?category=chat-friends";
    
    if (isSetAndNotEmptyObject($_POST, "message")) {
        $content = strip_tags($_POST["message"]);
        $sendTime = new DateTime();
        $sendTime = $sendTime->format("Y-m-d H:i:s");
        $newMsg = new ForumMsg(["content" => $content, "send_time" => $sendTime, "gameId" => $game->getId(), "forumPosterId" => (int) $_SESSION["user"]]);
        $messageRepo->add($newMsg);
    }
} else {
    header("Location:feed.php?category=chat-friends");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Forum de <?php echo $headerName; ?></title>
</head>
<body>
    <?php require "include/messageHeader.php"; ?>
    <main class="content_friend_message">
        <?php
            $content = $messageRepo->getConversation($game->getId());
            if (count($content) <= 0) { ?>
                <h2>Il n'y a pas de message dans ce forum...<br>Lancez-vous !</h2>
            <?php } else {
                foreach ($content as $message) {
                    $sender = $userRepo->get($message->getForumPosterId());
                    require "templates/forumMessageCard.php";
                }
            }
        ?>
    </main>
    <form action="" method="post" class="message_form">
        <textarea name="message" id="message" class="message_bar" placeholder="Tapez votre Message"></textarea>
        <button type="submit" class="message_send"><i class="bi bi-send"></i></button>
    </form>
    <?php require "include/footer.php"; ?>
</body>
</html>