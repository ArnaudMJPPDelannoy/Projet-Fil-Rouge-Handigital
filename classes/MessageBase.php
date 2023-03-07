<?php
abstract class MessageBase {
    protected int $_id;
    protected string $_content;
    protected DateTime $_sendTime;

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $value) {
            $method = "set" . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

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
        return $this->_sendTime;
    }

    public function setSendTime(DateTime $newSendTime)
    {
        $this->_sendTime = $newSendTime;
    }
}
?>