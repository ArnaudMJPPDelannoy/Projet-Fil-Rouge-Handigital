<?php
$content = $article->getContent();
if (strlen($content) > 75) {
    $content = substr($content, 0, 75);
    $content = $content . "...";
}
?>

<article class="card" id="article_card_<?php echo $article->getId() ?>">
    <h2><?php echo $article->getTitle() ?></h2>
    <p><?php echo $content ?></p>
</article>