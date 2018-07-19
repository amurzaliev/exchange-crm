<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var ExchangeOffice[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ExchangeOffice", mappedBy="user")
     */
    private $exchangeOffices;


    public function __construct()
    {
        parent::__construct();
        $this->exchangeOffices = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setExchangeOffices(Collection $exchangeOffices): self
    {
        $this->exchangeOffices = $exchangeOffices;
        return $this;
    }

    public function getExchangeOffices(): Collection
    {
        return $this->exchangeOffices;
    }

    public function addExchangeOffice(ExchangeOffice $exchangeOffice)
    {
        if (!$this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->add($exchangeOffice);
        }
    }

    public function removeExchangeOffice(ExchangeOffice $exchangeOffice)
    {
        if ($this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->removeElement($exchangeOffice);
        }
    }
}
