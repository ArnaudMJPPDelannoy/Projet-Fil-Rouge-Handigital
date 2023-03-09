<?php
class ForumMsg extends MessageBase {
    private int $_gameId;
    private int $_forumPosterId;

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if ($key == "Id_ForumMsg") {
                $this->setId($value);
            } else if ($key == "send_time") {
                $this->setSendTime($value);
            }  else if ($key == "Id_Games") {
                $this->setGameId($value);
            } else if ($key == "Id_ForumPoster") {
                $this->setForumPosterId($value);
            }
        }
    }

    public function getGameId()
    {
        return $this->_gameId;
    }

    public function setGameId(int $newGameId)
    {
        if ($newGameId > 0) {
            $this->_gameId = $newGameId;
        }
    }

    public function getForumPosterId()
    {
        return $this->_forumPosterId;
    }

    public function setForumPosterId(int $newForumPosterId)
    {
        if ($newForumPosterId > 0) {
            $this->_forumPosterId = $newForumPosterId;
        }
    }
}
?>