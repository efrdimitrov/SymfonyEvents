<?php


namespace EventBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\UserRepository")
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string",length=255)
     */
    private $username;

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Event", mappedBy="author")
     */
    private $events;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="EventBundle\Entity\Birthday", mappedBy="author")
     */
    private $birthdays;

    /**
     * User constructor.
     * @param ArrayCollection $events
     * @param ArrayCollection $birthdays
     */
    public function __construct(ArrayCollection $events, ArrayCollection $birthdays)
    {
        $this->events = $events;
        $this->birthdays = $birthdays;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @var User
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param User $username
     * @return $this
     */
    public function setUsername(User $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return ArrayCollection
     */
    public function getEvents(): ArrayCollection
    {
        return $this->events;
    }

    /**
     * @param Event $event
     * @return User
     */
    public function addEvent(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getBirthdays(): ArrayCollection
    {
        return $this->birthdays;
    }

    /**
     * @param Birthday $birthday
     * @return User
     */
    public function addBirthday(Birthday $birthday)
    {
        $this->birthdays[] = $birthday;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }


    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}