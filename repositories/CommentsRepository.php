<?php
class CommentsRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function add(Comment $comment)
    {
        $content = $comment->getContent();
        $sendTime = $comment->getSendTime();
        $posterId = $comment->getPosterId();
        $articleId = $comment->getArticleId();

        $query = $this->_db->prepare("INSERT INTO `comments` (content, send_time, Id_Poster, Id_Articles) VALUES (:content, :sendtime, :poster, :article)");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":poster", $posterId);
        $query->bindValue(":article", $articleId);
        $query->execute();

        $comment->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    public function get(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `comments` WHERE Id_Comments = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new Comment($result);
    }

    public function update(Comment $comment)
    {
        $id = $comment->getId();
        $content = $comment->getContent();
        $sendTime = $comment->getSendTime();
        $posterId = $comment->getPosterId();
        $articleId = $comment->getArticleId();

        $query = $this->_db->prepare("UPDATE `comments` SET content = :content, send_time = :sendtime, Id_Poster = :poster, Id_Articles = :article WHERE Id_Comments = :id");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":poster", $posterId);
        $query->bindValue(":article", $articleId);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>