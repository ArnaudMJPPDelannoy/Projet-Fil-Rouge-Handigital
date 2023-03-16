<?php
    require "scripts/functions.php";

    if (isSetAndNotEmptyObject($_GET, "article_id")) {
        require "scripts/connect.php";
        require "scripts/checkConnect.php";
        $repository = new ArticlesRepository($pdo);
        $article = $repository->get((int) $_GET["article_id"]);
        $articleInfos = $repository->getWriteInfo($article->getId());

        if (!isset($article) || empty($article)) {
            header("Location:feed.php");
        } else {
            if (isSetAndNotEmptyObject($_GET, "delete")) {
                $repository->delete($article);
                header("Location:feed.php?category=news");
            }
            if (isSetAndNotEmptyObject($_POST, "comment")) {
                require "scripts/connect.php";
                $content = strip_tags($_POST["comment"]);
                $sendTime = new DateTime();
                $sendTimeStr = $sendTime->format("Y-m-d H:i:s");
                $commentsRepo = new CommentsRepository($pdo);
                $comment = new Comment([
                    "content" => $content,
                    "sendTime" => $sendTimeStr,
                    "posterId" => $_SESSION["user"],
                    "articleId" => $article->getId(),
                ]);
                $commentsRepo->add($comment);
            }

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
    <a href="feed.php?category=news" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
    <div class="banner">
        <?php if (!empty($articleBanner) && strlen($articleBanner) > 0) { ?>
            <img src="<?php echo $articleBanner; ?>" alt="Bannière de l'Article">
        <?php } ?>
    </div>
    <main class="content_no_header">
        <h1><?php echo $articleTitle; ?></h1>
        <p>Écrit par : <?php echo $articleInfos["writer"]->getUsername(); ?></p>
        <p>Date : <?php echo $articleInfos["publishTime"]; ?></p>
        <p class="article-content"><?php echo $articleContent; ?></p>
        <h2>Commentaires</h2>
        <?php if (count($articleComments) <= 0) { ?>
            <p>Il n'y a pas de commentaires.</p>
        <?php } else {
            foreach ($articleComments as $comment) {
                require "templates/commentCard.php";
            }
        }
        ?>
        <h2>Commentez :</h2>
        <form action="" method="post" class="comment_form">
            <textarea name="comment" id="comment" cols="25" rows="10" placeholder="Écrivez votre commentaire ici." required></textarea>
            <input type="submit" value="Envoyer" class="button">
        </form>
        <br><br>
        <?php
        $userRepo = new UsersRepository($pdo);
        $curUser = $userRepo->get($_SESSION["user"]);
        if ($curUser->getRole() == "admin") { ?>
            <a href="articlePage.php?article_id=<?php echo $article->getId(); ?>&delete=true" class="button red-button">Effacer l'Article</a>
        <?php } ?>
    </main>
    <?php require "include/footer.php"; ?>
</body>
</html>