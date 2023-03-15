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
        $bannerUrl = str_replace("img/", "", $game->getBannerImageUrl());
        $iconUrl = str_replace("img/", "", $game->getIconImageUrl());

        $query = $this->_db->prepare("INSERT INTO `games` (name, description, banner_image_url, icon_image_url) VALUES (:name, :desc, :banner, :icon)");
        $query->bindValue(":name", $name);
        $query->bindValue(":desc", $description);
        $query->bindValue(":banner", $bannerUrl);
        $query->bindValue(":icon", $iconUrl);
        $query->execute();

        $game->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    /**
     * Links a Game to a GameGenre in the Database
     *
     * @param   Game       $game   The Game to link
     * @param   GameGenre  $genre  The GameGenre to link
     *
     * @return  void             
     */
    public function addLinkToGenre(Game $game, GameGenre $genre)
    {
        $gameId = $game->getId();
        $genreId = $genre->getId();

        $query = $this->_db->prepare("INSERT INTO `whichgenres` (Id_Games, Id_GameGenres) VALUES (:gameId, :genreId)");
        $query->bindValue(":gameId", $gameId);
        $query->bindValue(":genreId", $genreId);
        $query->execute();
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

        $genreRepo = new GameGenresRepository($this->_db);

        foreach ($results as $gameGenreLink) {
            $genres[] = $genreRepo->get((int) $gameGenreLink["Id_GameGenres"]);
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
        $bannerUrl = str_replace("img/", "", $game->getBannerImageUrl());
        $iconUrl = str_replace("img/", "", $game->getIconImageUrl());

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

    /**
     * Deletes the link between a Game and a GameGenre
     *
     * @param   Game       $game   The Game object
     * @param   GameGenre  $genre  The GameGenre object
     *
     * @return  void             
     */
    public function deleteLinkToGenre(Game $game, GameGenre $genre)
    {
        $gameId = $game->getId();
        $genreId = $genre->getId();

        $query = $this->_db->prepare("DELETE FROM `whichgenres` WHERE `Id_Games` = :gameId AND `Id_GameGenres` = :genreId");
        $query->bindValue(":gameId", $gameId);
        $query->bindValue(":genreId", $genreId);
        $query->execute();
    }

    public function deleteAllGenres(Game $game)
    {
        $gameId = $game->getId();

        $query = $this->_db->prepare("DELETE FROM `whichgenres` WHERE `Id_Games` = :gameId");
        $query->bindValue(":gameId", $gameId);
        $query->execute();
    }

    /**
     * Searches a string in the Name or Description (or both) of the Database's Games
     *
     * @param   string  $searchStr   The string to search for
     * @param   bool  $searchName  Does it search in the Game's Name?
     * @param   bool  $searchDesc  Does it search in the Game's Description?
     *
     * @return  array               An Array of Games.
     */
    public function search(string $searchStr, bool $searchName, bool $searchDesc)
    {
        $searchStr = strip_tags($searchStr);
        $search = "SELECT * FROM `games` WHERE ";
        if ($searchName && $searchDesc) {
            $search = $search . "`name` LIKE \"%" . $searchStr . "%\" OR `description` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchName) {
            $search = $search . "`name` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchDesc) {
            $search = $search . "`description` LIKE \"%" . $searchStr . "%\"";
        } else {
            return [];
        }
        $query = $this->_db->prepare($search);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $searchGames = array_map(function($gameData) {
            return new Game($gameData);
        }, $results);

        return $searchGames;
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>