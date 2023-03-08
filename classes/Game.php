<?php
class Game {
    private int $_id;
    private string $_name;
    private string $_description;
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
            } else if ($key == "Id_Games") {
                $this->setId($value);
            } else if ($key == "banner_image_url") {
                $this->setBannerImageUrl($value);
            } else if ($key == "icon_image_url") {
                $this->setIconImageUrl($value);
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

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription(string $newDesc)
    {
        $this->_description = $newDesc;
    }

    public function getBannerImageUrl()
    {
        return $this->_bannerImageUrl;
    }

    public function setBannerImageUrl(string $newUrl)
    {
        $this->_bannerImageUrl = $newUrl;
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