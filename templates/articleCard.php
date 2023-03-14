<?php
$articleId = $article->getId();
$articleContent = $article->getContent();
if (strlen($articleContent) > 75) {
    $articleContent = substr($articleContent, 0, 75);
    $articleContent = $articleContent . "...";
}
?>

<a href="articlePage.php?article_id=<?php echo $articleId; ?>" class="article_card_link">
    <article class="card" id="article_card_<?php echo $articleId; ?>">
        <h2><?php echo $article->getTitle(); ?></h2>
        <p><?php echo $articleContent; ?></p>
    </article>
</a>