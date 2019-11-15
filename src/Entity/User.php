<?php
// src/Entity/User.php
namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="cet Email existe déjà")
 * @UniqueEntity(fields="username", message="Nom déjà utilisé")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;


    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;


    /**
     * @ORM\Column(name="roles", type="string", length=64)
     */
    private $roles="ROLE_USER";


    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $nom = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $ville = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $codePostal = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresse = '';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $tokenMail = '';

    // /////////////////

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }


    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }


    public function getRoles()
    {
        if ($this->roles)
            return [$this->roles];
        else
            return ['ROLE_USER'];
    }
/////////////////////////////////////////////////////////

    public function setRoles($roles)
    {
        $this->roles = $roles;
        // allows for chaining
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getisActive()
    {
        return $this->isActive;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    public function getVille()
    {
        return $this->ville;
    }


    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }


    public function getCodePostal()
    {
        return $this->codePostal;
    }


    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;
        return $this;
    }


    public function getAdresse()
    {
        return $this->adresse;
    }


    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTokenMail()
    {
        return $this->tokenMail;
    }


    public function setTokenMail($tokenMail)
    {
        $this->tokenMail = $tokenMail;
        return $this;
    }


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return "username : ".$this->getUsername()." role: ".$this->getRoles()[0]." mdp:".$this->getPassword();
    }
}