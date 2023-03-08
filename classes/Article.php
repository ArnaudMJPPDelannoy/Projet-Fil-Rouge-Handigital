<?php
class Article {
    private int $_id;
    private string $_title;
    private string $_content;
    private string $_bannerImageUrl;

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
            } else if ($key == "Id_Articles") {
                $this->setId($value);
            } else if ($key == "banner_image_url") {
                $value = isset($value) ? $value : "";
                $this->setBannerImageUrl($value);
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

    public function getTitle()
    {
        return $this->_title;
    }

    public function setTitle(string $newTitle)
    {
        $this->_title = $newTitle;
    }

    public function getContent()
    {
        return $this->_content;
    }

    public function setContent(string $newContent)
    {
        $this->_content = $newContent;
    }

    public function getBannerImageUrl()
    {
        if (!isset($this->_bannerImageUrl)) return "";
        return $this->_bannerImageUrl;
    }

    public function setBannerImageUrl(string $newBannerUrl)
    {
        $this->_bannerImageUrl = $newBannerUrl;
    }
}
?>