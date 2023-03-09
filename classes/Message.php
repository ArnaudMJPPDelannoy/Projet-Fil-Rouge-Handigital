<?php
class Message extends MessageBase {
    private int $_senderId;
    private int $_receiverId;

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            } else if ($key == "Id_Messages") {
                $this->setId($value);
            } else if ($key == "send_time") {
                $this->setSendTime($value);
            } else if ($key == "Id_Sender") {
                $this->setSenderId($value);
            } else if ($key == "Id_Receiver") {
                $this->setReceiverId($value);
            }
        }
    }

    public function getSenderId()
    {
        return $this->_senderId;
    }

    public function setSenderId(int $newSenderId)
    {
        if ($newSenderId > 0) {
            $this->_senderId = $newSenderId;
        }
    }

    public function getReceiverId()
    {
        return $this->_receiverId;
    }

    public function setReceiverId(int $newReceiverId)
    {
        if ($newReceiverId > 0) {
            $this->_receiverId = $newReceiverId;
        }
    }
}
?>