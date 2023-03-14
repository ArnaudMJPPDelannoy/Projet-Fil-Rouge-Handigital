<?php
class GameGenresRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    /**
     * Adds a GameGenre to the Database
     *
     * @param   GameGenre  $gameGenre  The GameGenre to add
     *
     * @return  void                 
     */
    public function add(GameGenre $gameGenre)
    {
        $name = $gameGenre->getName();
        $query = $this->_db->prepare("INSERT INTO `gamegenres` (name) VALUES (:name)");
        $query->bindValue(":name", $name);
        $query->execute();

        $gameGenre->hydrate(["id" => $this->_db->lastInsertId()]);
    }
    /**
     * Returns a GameGenre from the Database
     *
     * @param   mixed  $info  Int if searching with Id, String if searching with Name
     *
     * @return  GameGenre         The GameGenre from the Database
     */
    public function get($info)
    {
        if (is_int($info)) {
            $query = $this->_db->prepare("SELECT * FROM `gamegenres` WHERE `Id_GameGenres` = :info");
        } else {
            $query = $this->_db->prepare("SELECT * FROM `gamegenres` WHERE `name` = :info");
        }
        $query->bindValue(":info", $info);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new GameGenre($result);
    }

    /**
     * Updates a GameGenre in the Database
     *
     * @param   GameGenre  $gameGenre  The GameGenre to update
     *
     * @return  void                 
     */
    public function update(GameGenre $gameGenre)
    {
        $id = $gameGenre->getId();
        $name = $gameGenre->getName();

        $query = $this->_db->prepare("UPDATE `gamegenres` SET `name` = :name WHERE `Id_GameGenres` = :id");
        $query->bindValue(":name", $name);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Deletes a GameGenre from the Database
     *
     * @param   GameGenre  $gameGenre  The GameGenre to delete
     *
     * @return  void                 
     */
    public function delete(GameGenre $gameGenre)
    {
        $id = $gameGenre->getId();

        $query = $this->_db->prepare("DELETE FROM `gamegenres` WHERE `Id_GameGenres` = :id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Checks if a GameGenre exists
     *
     * @param   mixed  $info  Int if searching by Id, string if searching by Name
     *
     * @return  bool         Does the GameGenre exist?
     */
    public function exists($info)
    {
        if (is_int($info)) {
            $query = $this->_db->prepare("SELECT * FROM `gamegenres` WHERE `Id_GameGenres` = :info");
        } else {
            $query = $this->_db->prepare("SELECT * FROM `gamegenres` WHERE `name` = :info");
        }
        $query->bindValue(":info", $info);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return isset($result) && !empty($result);
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>