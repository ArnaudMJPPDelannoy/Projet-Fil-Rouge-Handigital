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
     * Returns a User from the Databse
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
        $profilePicture = $user->getProfileImageUrl();

        $query = $this->_db->prepare("UPDATE `users` SET lastname = :lname, firstname = :fname, age = :age, gender = :gender, email = :email, username = :uname, password = :pass, profile_image_url = :profimgurl WHERE `Id_Users` = :id");
        $query->bindValue(":lname", $lastName);
        $query->bindValue(":fname", $firstName);
        $query->bindValue(":age", $age);
        $query->bindValue(":gender", $gender);
        $query->bindValue(":email", $email);
        $query->bindValue(":uname", $userName);
        $query->bindValue(":pass", $password);
        $query->bindValue(":profimgurl", $profilePicture);

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