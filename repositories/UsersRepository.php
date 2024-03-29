<?php
class UsersRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    /**
     * Adds a User to the Database
     *
     * @param   User  $user  The User to add.
     *
     * @return  void       
     */
    public function add(User $user)
    {
        $lastName = $user->getLastname();
        $firstName = $user->getFirstname();
        $age = $user->getAge();
        $gender = $user->getGender();
        $email = $user->getEmail();
        $userName = $user->getUsername();
        $password = $user->getPassword();

        $query = $this->_db->prepare("INSERT INTO `users` (lastname, firstname, age, gender, email, username, password) VALUES (:lname, :fname, :age, :gender, :email, :uname, :pass)");
        $query->bindValue(":lname", $lastName);
        $query->bindValue(":fname", $firstName);
        $query->bindValue(":age", $age);
        $query->bindValue(":gender", $gender);
        $query->bindValue(":email", $email);
        $query->bindValue(":uname", $userName);
        $query->bindValue(":pass", $password);
        $query->execute();

        $user->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    public function addFriend(int $idUser, int $idFriend)
    {
        if ($idUser <= 0 || $idFriend <= 0) return;
        $query = $this->_db->prepare("INSERT INTO `friends` (Id_Users, Id_Friend) VALUES (:user, :friend)");
        $query->bindValue(":user", $idUser);
        $query->bindValue(":friend", $idFriend);
        $query->execute();
    }

    /**
     * Adds a link between a User and a Game
     *
     * @param   int  $idUser  The Id of the User
     * @param   int  $idGame  The Id of the Game
     *
     * @return  void        
     */
    public function addPlayedGame(int $idUser, int $idGame)
    {
        if ($idUser <= 0 || $idGame <= 0) return;
        $query = $this->_db->prepare("INSERT INTO `play` (Id_Users, Id_Games) VALUES (:user, :game)");
        $query->bindValue(":user", $idUser);
        $query->bindValue(":game", $idGame);
        $query->execute();
    }

    /**
     * Returns a User from the Database
     *
     * @param   mixed  $info  Either an int if searching with Id or a string if searching by UserName.
     *
     * @return  User         The User if found.
     */
    public function get($info)
    {
        if (is_int($info)) {
            $query = $this->_db->prepare("SELECT * FROM `users` WHERE `Id_Users` = :info");
        } else {
            $query = $this->_db->prepare("SELECT * FROM `users` WHERE `username` = :info");
        }
        $query->bindValue(":info", $info);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new User($result);
    }

    /**
     * Returns all Users from the Database.
     *
     * @return  array  An Array of Users
     */
    public function getAll()
    {
        $query = $this->_db->prepare("SELECT * FROM `users`");
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $allUsers = array_map(function($userData) {
            return new User($userData);
        }, $results);
        return $allUsers;
    }

    /**
     * Returns the friends of a User.
     *
     * @param   int  $id  The Id of the User.
     *
     * @return  array    An array of Users.
     */
    public function getFriends(int $id)
    {
        if ($id <= 0) return [];
        $query = $this->_db->prepare("SELECT * FROM `friends` WHERE `Id_Users` = :id");
        $query->bindValue(":id", $id);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $friends = [];

        foreach ($result as $friend) {
            $friends[] = $this->get((int) $friend["Id_Friend"]);
        }

        return $friends;
    }

    /**
     * Checks if a User is friends with another with their Ids
     *
     * @param   int  $userId    Id of the first User
     * @param   int  $friendId  Id of the second User
     *
     * @return  bool          Are they friends?
     */
    public function isFriend(int $userId, int $friendId)
    {
        $query = $this->_db->prepare("SELECT * FROM `friends` WHERE `Id_Users` = :user AND `Id_Friend` = :friend");
        $query->bindValue(":user", $userId);
        $query->bindValue(":friend", $friendId);
        $query->execute();

        $result = $query->fetch();
        return isset($result) && !empty($result);
    }

    /**
     * Returns all the Games a User has in their favorites.
     *
     * @param   int  $id  The id of the User
     *
     * @return  array    An Array of Games.
     */
    public function getPlayedGames(int $id)
    {
        if ($id <= 0) return [];

        $query = $this->_db->prepare("SELECT * FROM `play` WHERE `Id_Users` = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $playedGames = [];

        $gameRepo = new GamesRepository($this->_db);

        foreach ($result as $game) {
            $playedGames[] = $gameRepo->get((int) $game["Id_Games"]);
        }

        return $playedGames;
    }

    /**
     * Updates a User's informations in the Database.
     *
     * @param   User  $user  The User to update.
     *
     * @return  void       
     */
    public function update(User $user)
    {
        $id = $user->getId();
        $lastName = $user->getLastname();
        $firstName = $user->getFirstname();
        $age = $user->getAge();
        $gender = $user->getGender();
        $email = $user->getEmail();
        $userName = $user->getUsername();
        $password = $user->getPassword();
        $profilePicture = str_replace("img/", "", $user->getProfileImageUrl());

        $query = $this->_db->prepare("UPDATE `users` SET lastname = :lname, firstname = :fname, age = :age, gender = :gender, email = :email, username = :uname, password = :pass, profile_image_url = :profimgurl WHERE `Id_Users` = :id");
        $query->bindValue(":lname", $lastName);
        $query->bindValue(":fname", $firstName);
        $query->bindValue(":age", $age);
        $query->bindValue(":gender", $gender);
        $query->bindValue(":email", $email);
        $query->bindValue(":uname", $userName);
        $query->bindValue(":pass", $password);
        $query->bindValue(":profimgurl", $profilePicture);
        $query->bindValue(":id", $id);

        $query->execute();
    }

    /**
     * Deletes a User from the Database.
     *
     * @param   User  $user  The User to delete.
     *
     * @return  void       
     */
    public function delete(User $user)
    {
        $id = $user->getId();

        $query = $this->_db->prepare("DELETE FROM `users` WHERE `Id_Users` = :id");
        $query->bindValue(":id", $id);
        
        $query->execute();
    }

    public function deleteFriend(int $idUser, int $idFriend)
    {
        $query = $this->_db->prepare("DELETE FROM `friends` WHERE `Id_Users` = :user AND `Id_Friend` = :friend");
        $query->bindValue(":user", $idUser);
        $query->bindValue(":friend", $idFriend);
        $query->execute();
    }

    public function deletePlayedGame(int $idUser, int $idGame)
    {
        $query = $this->_db->prepare("DELETE FROM `play` WHERE `Id_Users` = :user AND `Id_Games` = :game");
        $query->bindValue(":user", $idUser);
        $query->bindValue(":game", $idGame);
        $query->execute();
    }

    public function connectUser(User $user) {
        $userId = $user->getId();

        $disconnectDate = new DateTime();
        $disconnectDate = $disconnectDate->add(new DateInterval("PT1H"));

        $query = $this->_db->prepare("UPDATE `users` SET connected = true, disconnect_date = :disconnectDate WHERE `Id_Users` = :id");
        $query->bindValue(":disconnectDate", $disconnectDate->format("Y-m-d H:i:s"));
        $query->bindValue(":id", $userId);
        $query->execute();
    }

    public function updateConnect(User $user)
    {
        $userId = $user->getId();
        $disconnectDate = $user->getDisconnectDate();

        $query = $this->_db->prepare("UPDATE `users` SET connected = true, disconnect_date = :discDate WHERE `Id_Users` = :id");
        $query->bindValue(":discDate", $disconnectDate->format("Y-m-d H:i:s"));
        $query->bindValue(":id", $userId);
        $query->execute();
    }

    public function disconnectUser(User $user) {
        $userId = $user->getId();

        $query = $this->_db->prepare("UPDATE `users` SET connected = false, disconnect_date = \"unset\" WHERE `Id_Users` = :id");
        $query->bindValue(":id", $userId);
        $query->execute();
    }

    /**
     * Checks if a User exists in the Database.
     *
     * @param   mixed  $info  Either an int if searching with Id or a string if searching with UserName.
     *
     * @return  bool         Returns whether the User exists or not.
     */
    public function exists($info)
    {
        if (is_int($info)) {
            $query = $this->_db->prepare("SELECT * FROM `users` WHERE `Id_Users` = :info");
        } else if (is_string($info)) {
            $query = $this->_db->prepare("SELECT * FROM `users` WHERE `username` = :info");
        } else {
            return false;
        }
        $query->bindValue(":info", $info);
        $query->execute();

        $result = $query->fetch();
        return isset($result) && !empty($result);
    }

    public function search(string $searchStr)
    {
        $searchStr = strip_tags($searchStr);

        $search = "SELECT * FROM `users` WHERE `username` LIKE \"%" . $searchStr . "%\"";
        $query = $this->_db->prepare($search);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $searchUsers = array_map(function($userData) {
            return new User($userData);
        }, $results);

        return $searchUsers;
    }

    /**
     * Searches a string in the Name or Description (or both) of a User's Played Games
     *
     * @param   int     $userId             The Id of the User
     * @param   string  $searchStr          The string to search
     * @param   bool    $searchName         Does it search in the Game's Name?
     * @param   bool    $searchDescription  Does it search in the Game's Description?
     *
     * @return  array                      An Array of Games
     */
    public function searchPlayedGames(int $userId, string $searchStr, bool $searchName, bool $searchDescription)
    {
        $searchStr = strip_tags($searchStr);

        $playedGames = $this->getPlayedGames($userId);
        $playedGamesIds = array_map(function($elem) {
            return $elem->getId();
        }, $playedGames);


        $search = "SELECT * FROM `games` WHERE `Id_Games` IN (" . implode(',', $playedGamesIds) . ")";
        if ($searchName && $searchDescription) {
            $search = $search . " AND `name` LIKE \"%" . $searchStr . "%\" OR `description` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchName) {
            $search = $search . " AND `name` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchDescription) {
            $search = $search . " AND `description` LIKE \"%" . $searchStr . "%\"";
        } else {
            return [];
        }
        $query = $this->_db->prepare($search);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $searchGames = [];

        foreach ($results as $gameData) {
            $searchGames[] = new Game($gameData);
        }

        return $searchGames;
    }

    /**
     * Searches a string in the Name of the User's Friends
     *
     * @param   int     $userId     The Id of the User
     * @param   string  $searchStr  The string to search for
     *
     * @return  array              An Array of Users
     */
    public function searchFriends(int $userId, string $searchStr)
    {
        $searchStr = strip_tags($searchStr);

        $friends = $this->getFriends($userId);
        $friendsIds = array_map(function($user) {
            return $user->getId();
        }, $friends);

        $search = "SELECT * FROM `users` WHERE `Id_Users` IN (" . implode(",", $friendsIds) . ") AND `username` LIKE \"%" . $searchStr . "%\"";
        $query = $this->_db->prepare($search);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $searchFriends = array_map(function($userData) {
            return new User($userData);
        }, $results);

        return $searchFriends;
    }

    /**
     * Sets the Database to use.
     *
     * @param   PDO  $db  The PDO containing the link to the Database.
     *
     * @return  void    
     */
    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>