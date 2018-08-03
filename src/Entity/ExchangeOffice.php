<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeOfficeRepository")
 */
class ExchangeOffice
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="exchangeOffices")
     */
    private $user;

    /**
     * @var Cashbox[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Cashbox", mappedBy="exchangeOffice")
     */
    private $cashboxes;

    public function __construct()
    {
        $this->cashboxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCashboxes(): Collection
    {
        return $this->cashboxes;
    }

    public function addCashbox(Cashbox $cashbox): self
    {
        if (!$this->cashboxes->contains($cashbox)) {
            $this->cashboxes->add($cashbox);
            $cashbox->setExchangeOffice($this);
        }

        return $this;
    }

    public function removeCashbox(Cashbox $cashbox): self
    {
        if ($this->cashboxes->contains($cashbox)) {
            $this->cashboxes->removeElement($cashbox);
            if ($cashbox->getExchangeOffice() === $this) {
                $cashbox->setExchangeOffice(null);
            }
        }

        return $this;
    }

}
