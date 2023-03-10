<?php
$commenterId = $comment->getPosterId();
$content = $comment->getContent();

require "scripts/connect.php";
$userRepo = new UsersRepository($pdo);
$commenter = $userRepo->get($commenterId);

$url = $commenter->getId() == $_SESSION["user"] ? "userProfile.php?previous_url=articlePage.php?article_id=" . $article->getId() : "otherProfile.php?user_id=" . $commenterId . "&previous_url=articlePage.php?article_id=" . $article->getId();
?>

<a href="<?php echo $url; ?>" class="comment_card_link">
    <article class="card comment_card">
        <img src="<?php echo $commenter->getProfileImageUrl(); ?>" alt="Icône du posteur.">
        <div>
            <h2><?php echo $commenter->getUsername(); ?></h2>
            <p><?php echo $content; ?></p>
        </div>
    </article>
</a>