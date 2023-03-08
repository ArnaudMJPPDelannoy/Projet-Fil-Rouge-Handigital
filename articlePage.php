<?php
    require "scripts/functions.php";

    if (isSetAndNotEmptyObject($_GET, "article_id")) {
        require "scripts/connect.php";
        $repository = new ArticlesRepository($pdo);
        $article = $repository->get((int) $_GET["article_id"]);

        if (!isset($article) || empty($article)) {
            header("Location:feed.php");
        } else {
            $articleTitle = $article->getTitle();
            $articleContent = $article->getContent();
            $articleBanner = $article->getBannerImageUrl();
            $articleComments = $repository->getComments($article->getId());
        }
    } else {
        header("Location:feed.php");
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
    <title><?php echo $articleTitle; ?></title>
</head>
<body>
    <main class="content_no_header">
        <a href="javascript:window.history.back();" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        <div class="banner">
            <?php if (strlen($articleBanner) > 0) { ?>
                <img src="<?php echo $articleBanner; ?>" alt="Bannière de l'Article">
            <?php } ?>
        </div>
        <h1><?php echo $articleTitle; ?></h1>
        <p class="article-content"><?php echo $articleContent; ?></p>
        <h2>Commentaires</h2>
        <?php if (count($articleComments) <= 0) { ?>
            <p>Il n'y a pas de commentaires.</p>
        <?php } else {
            foreach ($articleComments as $comment) {
                require "templates/commentCard.php";
            }
        }
        // Maybe put an area to comment here ?
        ?>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>