<?php
class ForumMsg extends MessageBase {
    private int $_gameId;
    private int $_forumPosterId;

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