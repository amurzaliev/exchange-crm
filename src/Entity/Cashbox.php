<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashboxRepository")
 */
class Cashbox
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="cashboxes")
     */
    private $user;

    /**
     * @var ExchangeOffice
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ExchangeOffice", inversedBy="cashboxes")
     */
    private $exchangeOffice;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Currency", inversedBy="cashboxes")
     */
    private $currency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var CurrencyRate[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CurrencyRate", mappedBy="cashboxCurrency")
     */
    private $currencyRates;

    /**
     * @var Transactions[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="сashboxFrom")
     */
    private $TransactionsFrom;

    /**
     * @var Transactions[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Transactions", mappedBy="сashboxTo")
     */
    private $transactionsTo;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->currencyRates = new ArrayCollection();
        $this->TransactionsFrom = new ArrayCollection();
        $this->transactionsTo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExchangeOffice(): ?ExchangeOffice
    {
        return $this->exchangeOffice;
    }

    public function setExchangeOffice(?ExchangeOffice $exchangeOffice): self
    {
        $this->exchangeOffice = $exchangeOffice;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCurrencyRates(): Collection
    {
        return $this->currencyRates;
    }

    public function addCurrencyRate(CurrencyRate $currencyRate): self
    {
        if (!$this->currencyRates->contains($currencyRate)) {
            $this->currencyRates->add($currencyRate);
            $currencyRate->setCashboxCurrency($this);
        }

        return $this;
    }

    public function removeCurrencyRate(CurrencyRate $currencyRate): self
    {
        if ($this->currencyRates->contains($currencyRate)) {
            $this->currencyRates->removeElement($currencyRate);
            if ($currencyRate->getCashboxCurrency() === $this) {
                $currencyRate->setCashboxCurrency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionsFrom(): Collection
    {
        return $this->TransactionsFrom;
    }

    public function addTransactionsFrom(Transactions $transactionsFrom): self
    {
        if (!$this->TransactionsFrom->contains($transactionsFrom)) {
            $this->TransactionsFrom[] = $transactionsFrom;
            $transactionsFrom->setCashboxFrom($this);
        }

        return $this;
    }

    public function removeTransactionsFrom(Transactions $transactionsFrom): self
    {
        if ($this->TransactionsFrom->contains($transactionsFrom)) {
            $this->TransactionsFrom->removeElement($transactionsFrom);
            // set the owning side to null (unless already changed)
            if ($transactionsFrom->getCashboxFrom() === $this) {
                $transactionsFrom->setCashboxFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transactions[]
     */
    public function getTransactionsTo(): Collection
    {
        return $this->transactionsTo;
    }

    public function addTransactionsTo(Transactions $transactionsTo): self
    {
        if (!$this->transactionsTo->contains($transactionsTo)) {
            $this->transactionsTo[] = $transactionsTo;
            $transactionsTo->setсashboxTo($this);
        }

        return $this;
    }

    public function removeTransactionsTo(Transactions $transactionsTo): self
    {
        if ($this->transactionsTo->contains($transactionsTo)) {
            $this->transactionsTo->removeElement($transactionsTo);
            // set the owning side to null (unless already changed)
            if ($transactionsTo->getсashboxTo() === $this) {
                $transactionsTo->setсashboxTo(null);
            }
        }

        return $this;
    }
}
