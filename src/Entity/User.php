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
    const ROLE_DEFAULT = 'ROLE_USER';

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string",name="full_name", nullable=true)
     */
    protected $fullName;

    /**
     * @var ExchangeOffice[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\ExchangeOffice", mappedBy="user")
     */
    private $exchangeOffices;


    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_OWNER']);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setEmail($email): self
    {
        $this->setUsername($email);

        return parent::setEmail($email);
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

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }
}
