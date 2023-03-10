<?php
class ForumMsgRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function add(ForumMsg $message)
    {
        $content = $message->getContent();
        $sendTime = $message->getSendTime();
        $forumPosterId = $message->getForumPosterId();
        $gameId = $message->getGameId();

        $query = $this->_db->prepare("INSERT INTO `forummsg` (content, send_time, Id_ForumPoster, Id_Games) VALUES (:content, :sendtime, :sender, :game)");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":sender", $forumPosterId);
        $query->bindValue(":game", $gameId);
        $query->execute();

        $message->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    public function get(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `forummsg` WHERE Id_ForumMsg = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new ForumMsg($result);
    }

    public function getConversation(int $gameId)
    {
        $messages = [];

        $query = $this->_db->prepare("SELECT * FROM `forummsg` WHERE `Id_Games` = :game");
        $query->bindValue(":game", $gameId);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $messageData) {
            $messages[] = new ForumMsg($messageData);
        }

        usort($messages, function(ForumMsg $a, ForumMsg $b) {
            $dateA = $a->getSendDateTime();
            $dateB = $b->getSendDateTime();

            if ($dateA < $dateB) {
                return -1;
            } else {
                return 1;
            }
        });

        return $messages;
    }

    public function update(ForumMsg $message)
    {
        $id = $message->getId();
        $content = $message->getContent();
        $sendTime = $message->getSendTime();
        $forumPosterId = $message->getForumPosterId();
        $gameId = $message->getGameId();

        $query = $this->_db->prepare("UPDATE `forummsg` SET content = :content, send_time = :sendtime, Id_ForumPoster = :sender, Id_Games = :game WHERE Id_ForumMsg = :id");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":sender", $forumPosterId);
        $query->bindValue(":game", $gameId);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>