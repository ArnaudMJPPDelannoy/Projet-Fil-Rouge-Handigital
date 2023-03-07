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
        $bannerImgUrl = $article->getBannerImageUrl();

        $query = $this->_db->prepare("INSERT INTO `articles` (title, content, banner_image_url) VALUES (:title, :content, :bannerimgurl)");
        $query->bindValue(":title", $title);
        $query->bindValue(":content", $content);
        $query->bindValue(":bannerimgurl", $bannerImgUrl);

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
        $banner = $article->getBannerImageUrl();

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

        $query = $this->_db->prepare("DELETE FROM `articles` WHERE `Id_Articles` = :id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}
?>