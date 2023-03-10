<?php
require "scripts/functions.php";
if (isSetAndNotEmptyObject($_GET, "user_id")) {
    require "scripts/connect.php";
    $userRepo = new UsersRepository($pdo);
    $user = $userRepo->get((int) $_GET["user_id"]);
    $headerName = $user->getUsername();
    $previousUrl = isSetAndNotEmptyObject($_GET, "previous_url") ? $_GET["previous_url"] : "feed.php?category=chat-friends";
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
    <title>Discussion avec <?php echo $headerName; ?></title>
</head>
<body>
    <?php require "include/messageHeader.php"; ?>
    <main class="content_friend_message">

    </main>
    <form action="" method="post" class="message_form">
        <input type="text" name="message" id="message" class="message_bar" placeholder="Tapez votre Message">
        <button type="submit" class="message_send"><i class="bi bi-send"></i></button>
    </form>
    <?php require "include/footer.php"; ?>
</body>
</html>