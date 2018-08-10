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

    /**
     * @var Transactions
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="exchangeOffice")
     */
    private $transactions;

    /**
     * @var Staff[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Staff", inversedBy="exchangeOffices")
     */
    private $staffs;

    public function __construct()
    {
        $this->cashboxes = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->staffs = new ArrayCollection();
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

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transactions $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setExchangeOffice($this);
        }

        return $this;
    }

    public function removeTransaction(Transactions $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getExchangeOffice() === $this) {
                $transaction->setExchangeOffice(null);
            }
        }

        return $this;
    }

    public function getStaffs(): Collection
    {
        return $this->staffs;
    }

    public function addStaff(Staff $staff): self
    {
        if (!$this->staffs->contains($staff)) {
            $this->staffs->add($staff);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): self
    {
        if ($this->staffs->contains($staff)) {
            $this->staffs->removeElement($staff);
        }

        return $this;
    }

}
