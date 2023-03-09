<?php
class GamesRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    /**
     * Adds a Game to the Database
     *
     * @param   Game  $game  The Game to add.
     *
     * @return  void       
     */
    public function add(Game $game)
    {
        $name = $game->getName();
        $description = $game->getDescription();
        $bannerUrl = $game->getBannerImageUrl();
        $iconUrl = $game->getIconImageUrl();

        $query = $this->_db->prepare("INSERT INTO `games` (name, description, banner_image_url, icon_image_url) VALUES (:name, :desc, :banner, :icon)");
        $query->bindValue(":name", $name);
        $query->bindValue(":desc", $description);
        $query->bindValue(":banner", $bannerUrl);
        $query->bindValue(":icon", $iconUrl);
        $query->execute();

        $game->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    /**
     * Returns a Game from the Database.
     *
     * @param   mixed  $info  Either the Id of the Game in the Database or it's name.
     *
     * @return  Game         The Game from the Database.
     */
    public function get($info)
    {
        if (is_int($info)) {
            $query = $this->_db->prepare("SELECT * FROM `games` WHERE `Id_Games` = :info");
        } else {
            $query = $this->_db->prepare("SELECT * FROM `games` WHERE `name` = :info");
        }
        $query->bindValue(":info", $info);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new Game($result);
    }

    /**
     * Returns all the Games in the Database
     *
     * @return  array  An Array of Games
     */
    public function getAll()
    {
        $query = $this->_db->prepare("SELECT * FROM `games`");
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $games = [];

        foreach ($results as $gameData)
        {
            $games[] = new Game($gameData);
        }
        
        return $games;
    }

    /**
     * Returns all Genres a Game is associated with
     *
     * @param   int  $id  The Id of the Game
     *
     * @return  array    An Array of GameGenres
     */
    public function getGenres(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `whichgenres` WHERE Id_Games = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $genres = [];

        foreach ($results as $genreData) {
            $genres[] = new GameGenre($genreData);
        }

        return $genres;
    }

    /**
     * Returns all Users who has this Game in their favorites
     *
     * @param   int  $id  The Id of the Game
     *
     * @return  array    An Array of Users
     */
    public function getPlayers(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `play` WHERE Id_Games = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $userRepo = new UsersRepository($this->_db);
        $users = [];

        foreach ($results as $userData) {
            $users[] = $userRepo->get($userData["Id_Users"]);
        }

        return $users;
    }

    /**
     * Updates a Game in the Database.
     *
     * @param   Game  $game  The Game to update.
     *
     * @return  void       
     */
    public function update(Game $game)
    {
        $id = $game->getId();
        $name = $game->getName();
        $description = $game->getDescription();
        $bannerUrl = $game->getBannerImageUrl();
        $iconUrl = $game->getIconImageUrl();

        $query = $this->_db->prepare("UPDATE `games` SET `name` = :name, `description` = :desc, `banner_image_url` = :banner, `icon_image_url` = :icon WHERE `Id_Games` = :id");
        $query->bindValue(":name", $name);
        $query->bindValue(":desc", $description);
        $query->bindValue(":banner", $bannerUrl);
        $query->bindValue(":icon", $iconUrl);
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Deletes a Game from the Database.
     *
     * @param   Game  $game  The Game to delete.
     *
     * @return  void       
     */
    public function delete(Game $game)
    {
        $id = $game->getId();

        $query = $this->_db->prepare("DELETE FROM `games` WHERE `Id_Games` = :id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>