<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Birthday
 *
 * @ORM\Table(name="birthdays")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\BirthdayRepository")
 */
class Birthday
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="born_on", type="datetime")
     */
    private $bornOn;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\User", inversedBy="birthdays")
     */
    private $author;

    /**
     * @var \DateTime
     */
    private $years;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Birthday
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set bornOn
     *
     * @param \DateTime $bornOn
     *
     * @return Birthday
     */
    public function setBornOn($bornOn)
    {
        $this->bornOn = $bornOn;

        return $this;
    }

    /**
     * Get bornOn
     *
     * @return \DateTime
     */
    public function getBornOn()
    {
        return $this->bornOn;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return Birthday
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    function getYears()
    {
        return floor((time() - strtotime($this->getBornOn()->format('Y-m-d'))) / 31556926);
    }

    public function setYears($years)
    {
        $this->years = $years;
    }
}

