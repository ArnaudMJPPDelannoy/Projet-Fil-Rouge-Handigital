<?php
if (isset($_GET["category"])) {
    $category = $_GET["category"];
} else {
    $category = "news";
}
switch ($category) {
    case "news":
        $title = "News";
        break;
    case "games":
        $title = "Jeux Préférés";
        break;
    case "friends":
        $title = "Liste d'Amis";
        break;
    case "chat":
        $title = "Messages";
        break;
    default:
        $category = "news";
        $title = "News";
        break;
}
$indicatorClass = "pos-" . $category;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title ?></title>
</head>
<body>
    <?php require "include/header.php"; ?>
    <main class="content" id="content">
        <h2>Mettre le contenu ici!!!</h2>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>