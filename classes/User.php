<?php
class User {
    private int $_id;
    private string $_lastName;
    private string $_firstName;
    private int $_age;
    private string $_gender;
    private string $_email;
    private string $_userName;
    private string $_password;
    private string $_profileImageUrl;
    private string $_role;
    private bool $_connected;
    private DateTime $_disconnectDate;

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
            } else if ($key == "Id_Users") {
                $this->setId($value);
            } else if ($key == "profile_image_url") {
                $value = isset($value) ? $value : "Placeholder.png";
                $this->setProfileImageUrl($value);
            } else if ($key == "disconnect_date") {
                $this->setDisconnectDate($value);
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

    public function getLastname()
    {
        return $this->_lastName;
    }

    public function setLastname(string $newLastName)
    {
        $this->_lastName = $newLastName;
    }

    public function getFirstname()
    {
        return $this->_firstName;
    }

    public function setFirstname(string $newFirstName)
    {
        $this->_firstName = $newFirstName;
    }

    public function getAge()
    {
        return $this->_age;
    }

    public function setAge($newAge)
    {
        if ($newAge >= 0) {
            $this->_age = $newAge;
        }
    }

    public function getGender()
    {
        return $this->_gender;
    }

    public function setGender(string $newGender)
    {
        $possibleGenders = ["man", "woman", "other"];
        if (in_array($newGender, $possibleGenders)) {
            $this->_gender = $newGender;
        }
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail(string $newMail)
    {
        if (filter_var($newMail, FILTER_VALIDATE_EMAIL)) {
            $this->_email = $newMail;
        }
    }

    public function getUsername()
    {
        return $this->_userName;
    }

    public function setUsername(string $newUserName)
    {
        $this->_userName = $newUserName;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setPassword(string $newPassword)
    {
        $this->_password = $newPassword;
    }

    public function getProfileImageUrl()
    {
        if (!isset($this->_profileImageUrl) || empty($this->_profileImageUrl)) return "img/Placeholder.png";
        return "img/" . $this->_profileImageUrl;
    }

    public function setProfileImageUrl(string $newUrl)
    {
        $this->_profileImageUrl = $newUrl;
    }

    public function getRole()
    {
        return $this->_role;
    }

    public function setRole(string $newRole)
    {
        $this->_role = $newRole;
    }

    public function getConnected()
    {
        $curDate = new DateTime();
        if ($curDate > $this->getDisconnectDate()) return false;
        return $this->_connected;
    }

    public function setConnected(bool $newStatus)
    {
        $this->_connected = $newStatus;
    }

    public function getDisconnectDate()
    {
        return $this->_disconnectDate;
    }

    public function setDisconnectDate(string $newDiscDate)
    {
        if (!isset($newDiscDate) || empty($newDiscDate) || $newDiscDate == "unset") {
            $this->_disconnectDate = new DateTime("999999-12-31");
            return;
        }
        $this->_disconnectDate = new DateTime($newDiscDate);
    }
}
?>