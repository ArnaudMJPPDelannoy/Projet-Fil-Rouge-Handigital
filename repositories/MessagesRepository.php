<?php
class MessagesRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function add(Message $message)
    {
        $content = $message->getContent();
        $sendTime = $message->getSendTime();
        $senderId = $message->getSenderId();
        $receiverId = $message->getReceiverId();

        $query = $this->_db->prepare("INSERT INTO `messages` (content, send_time, Id_Sender, Id_Receiver) VALUES (:content, :sendtime, :sender, :receiver)");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":sender", $senderId);
        $query->bindValue(":receiver", $receiverId);
        $query->execute();

        $message->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    public function get(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `messages` WHERE Id_Messages = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new Message($result);
    }

    public function getConversation(int $userId1, int $userId2)
    {
        $messages = [];

        $query = $this->_db->prepare("SELECT * FROM `messages` WHERE `Id_Sender` = :user1 AND `Id_Receiver` = :user2");
        $query->bindValue(":user1", $userId1);
        $query->bindValue(":user2", $userId2);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $messageData) {
            $messages[] = new Message($messageData);
        }

        $query = $this->_db->prepare("SELECT * FROM `messages` WHERE `Id_Sender` = :user1 AND `Id_Receiver` = :user2");
        $query->bindValue(":user1", $userId2);
        $query->bindValue(":user2", $userId1);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $messageData) {
            $messages[] = new Message($messageData);
        }

        usort($messages, function(Message $a, Message $b) {
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

    public function update(Message $message)
    {
        $id = $message->getId();
        $content = $message->getContent();
        $sendTime = $message->getSendTime();
        $senderId = $message->getSenderId();
        $receiverId = $message->getReceiverId();

        $query = $this->_db->prepare("UPDATE `messages` SET content = :content, send_time = :sendtime, Id_Sender = :sender, Id_Receiver = :receiver WHERE Id_Messages = :id");
        $query->bindValue(":content", $content);
        $query->bindValue(":sendtime", $sendTime);
        $query->bindValue(":sender", $senderId);
        $query->bindValue(":receiver", $receiverId);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>