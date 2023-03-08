<?php
$articleId = $article->getId();
$content = $article->getContent();
if (strlen($content) > 75) {
    $content = substr($content, 0, 75);
    $content = $content . "...";
}
?>

<a href="articlePage.php?article_id=<?php echo $articleId; ?>" class="article_card_link">
    <article class="card" id="article_card_<?php echo $articleId; ?>">
        <h2><?php echo $article->getTitle(); ?></h2>
        <p><?php echo $content; ?></p>
    </article>
</a>