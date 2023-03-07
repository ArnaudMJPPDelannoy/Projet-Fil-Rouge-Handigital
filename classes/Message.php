<?php
class Message extends MessageBase {
    private int $_senderId;
    private int $_receiverId;

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