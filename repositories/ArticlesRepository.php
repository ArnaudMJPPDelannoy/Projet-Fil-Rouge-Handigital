<?php
class ArticlesRepository {
    private PDO $_db;

    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    /**
     * Adds an Article to the Database
     *
     * @param   Article  $article  The Article to add.
     *
     * @return  void             
     */
    public function add(Article $article)
    {
        $title = $article->getTitle();
        $content = $article->getContent();
        $bannerImgUrl = str_replace("img/", "", $article->getBannerImageUrl());

        $query = $this->_db->prepare("INSERT INTO `articles` (title, content, banner_image_url) VALUES (:title, :content, :bannerimgurl)");
        $query->bindValue(":title", $title);
        $query->bindValue(":content", $content);
        $query->bindValue(":bannerimgurl", $bannerImgUrl);
        $query->execute();

        $article->hydrate(["id" => $this->_db->lastInsertId()]);
    }

    /**
     * Adds the link between an Article and the User who wrote it.
     *
     * @param   Article  $article  The Article to link to
     * @param   User     $writer   The User that wrote the Article
     *
     * @return  void             
     */
    public function addLinkToWriter(Article $article, User $writer)
    {
        $articleId = $article->getId();
        $userId = $writer->getId();
        $dateTime = new DateTime();

        $query = $this->_db->prepare("INSERT INTO `writearticle` (Id_Users, Id_Articles, publish_time) VALUES (:userId, :articleId, :sendDate)");
        $query->bindValue(":userId", $userId);
        $query->bindValue(":articleId", $articleId);
        $query->bindValue(":sendDate", $dateTime->format("Y-m-d H:i:s"));
        $query->execute();
    }

    /**
     * Returns an Article from the Database
     *
     * @param   int  $info  The Id of the Article in the Database.
     *
     * @return  Article         The Article.
     */
    public function get(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `articles` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);
        return new Article($result);
    }

    /**
     * Returns all Articles from the Database.
     *
     * @return  array  An array containing all the Articles found in the Database.
     */
    public function getAll()
    {
        $query = $this->_db->prepare("SELECT * FROM `articles`");
        $query->execute();

        $data = $query->fetchAll(PDO::FETCH_ASSOC);
        $articles = [];
        foreach ($data as $value) {
            $articles[] = new Article($value);
        }

        return $articles;
    }

    /**
     * Returns an Article's Comments
     *
     * @param   int  $id  The Id of the Article
     *
     * @return  array    An Array of Comments
     */
    public function getComments(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `comments` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $comments = [];

        foreach ($result as $comment) {
            $comments[] = new Comment($comment);
        }
        
        return $comments;
    }

    public function getWriteInfo(int $id)
    {
        $query = $this->_db->prepare("SELECT * FROM `writearticle` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $userRepo = new UsersRepository($this->_db);
        $writeInfo = [];
        $writeInfo["writer"] = $userRepo->get($result["Id_Users"]);
        $writeInfo["publishTime"] = $result["publish_time"];
        return $writeInfo;
    }
    
    /**
     * Updates an Article in the Database.
     *
     * @param   Article  $article  The Article to update.
     *
     * @return  void             
     */
    public function update(Article $article)
    {
        $id = $article->getId();
        $title = $article->getTitle();
        $content = $article->getContent();
        $banner = str_replace("img/", "", $article->getBannerImageUrl());

        $query = $this->_db->prepare("UPDATE `articles` SET title = :title, content = :content, banner_image_url = :banner_url WHERE Id_Articles = :id");
        $query->bindValue(":title", $title);
        $query->bindValue(":content", $content);
        $query->bindValue(":banner_url", $banner);
        $query->bindValue(":id", $id);

        $query->execute();
    }

    /**
     * Delete an Article from the Database
     *
     * @param   Article  $article  The Article to delete.
     *
     * @return  void             
     */
    public function delete(Article $article)
    {
        $id = $article->getId();

        $commentRepo = new CommentsRepository($this->_db);

        $commentRepo->deleteAllFromArticle($article);

        $query = $this->_db->prepare("DELETE FROM `writearticle` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();

        $query = $this->_db->prepare("DELETE FROM `articles` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Searches a string in the title or content (or both) of the Database's Articles
     *
     * @param   string  $searchStr      The string to search for.
     * @param   bool    $searchTitle    Does it search in the Article's Title?
     * @param   bool    $searchContent  Does it search in the Article's Content?
     *
     * @return  array                  An Array of Articles.
     */
    public function search(string $searchStr, bool $searchTitle, bool $searchContent)
    {
        $searchStr = strip_tags($searchStr);
        if ($searchTitle && $searchContent) {
            $search = "SELECT * FROM `articles` WHERE `title` LIKE \"%" . $searchStr . "%\" OR `content` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchTitle) {
            $search = "SELECT * FROM `articles` WHERE `title` LIKE \"%" . $searchStr . "%\"";
        } else if ($searchContent) {
            $search = "SELECT * FROM `articles` WHERE `content` LIKE \"%" . $searchStr . "%\"";
        } else {
            return [];
        }
        $query = $this->_db->prepare($search);
        $query->execute();

        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $searchResult = [];
        
        foreach ($results as $articleData) {
            $searchResult[] = new Article($articleData);
        }

        return $searchResult;
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>