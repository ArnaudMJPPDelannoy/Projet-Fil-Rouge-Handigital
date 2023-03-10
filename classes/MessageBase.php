<?php
abstract class MessageBase {
    protected int $_id;
    protected string $_content;
    protected DateTime $_sendTime;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    abstract public function hydrate(array $data);

    public function getId()
    {
        return $this->_id;
    }

    public function setId(int $newId)
    {
        if ($newId > 0) {
            $this->_id = $newId;
        }
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setContent(string $newContent)
    {
        $this->_content = $newContent;
    }

    public function getSendTime()
    {
        return $this->_sendTime->format("Y-m-d H:i:s");
    }

    public function getSendDateTime()
    {
        return $this->_sendTime;
    }

    public function setSendTime(string $newSendTime)
    {
        $dateTime = new DateTime($newSendTime);
        $this->_sendTime = $dateTime;
    }
}
?>