<?php
class Article {
    private int $_id;
    private string $_title;
    private string $_content;
    private string $_bannerImageUrl;
    private string $_iconImageUrl;

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
        return $this->_bannerImageUrl;
    }

    public function setBannerImageUrl(string $newBannerUrl)
    {
        $this->_bannerImageUrl = $newBannerUrl;
    }

    public function getIconImageUrl()
    {
        return $this->_iconImageUrl;
    }

    public function setIconImageUrl(string $newIconUrl)
    {
        $this->_iconImageUrl = $newIconUrl;
    }
}
?>