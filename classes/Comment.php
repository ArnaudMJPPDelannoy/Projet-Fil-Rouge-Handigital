<?php
class Comment extends MessageBase {
    private int $_posterId;
    private int $_articleId;

    public function getPosterId()
    {
        return $this->_posterId;
    }

    public function setPosterId(int $newPosterId)
    {
        if ($newPosterId > 0) {
            $this->_posterId = $newPosterId;
        }
    }

    public function getArticleId()
    {
        return $this->_articleId;
    }

    public function setArticleId($newArticleId)
    {
        if ($newArticleId > 0) {
            $this->_articleId = $newArticleId;
        }
    }
}
?>