<?php
// And the award of the most useless class goes to...
class GameGenre {
    private int $_id;
    private string $_name;

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
            } else if ($key == "Id_GameGenres") {
                $this->setId($value);
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

    public function getName()
    {
        return $this->_name;
    }

    public function setName(string $newName)
    {
        $this->_name = $newName;
    }
}
?>