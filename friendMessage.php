<?php
if (isSetAndNotEmptyObject($_GET, "user_id")) {
    require "scripts/connect.php";
    $userRepo = new UsersRepository($pdo);
    $user = $userRepo->get((int) $_GET["user_id"]);
    $userName = $user->getUsername();
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
    <title>Discussion avec <?php echo $userName; ?></title>
</head>
<body>
    
</body>
</html>