<?php
class Comment extends MessageBase {
    private int $_posterId;
    private int $_articleId;

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if ($key == "Id_Poster") {
                $this->setPosterId($value);
            } else if ($key == "Id_Articles") {
                $this->setArticleId($value);
            }
        }
    }

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