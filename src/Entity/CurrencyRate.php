<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRateRepository")
 */
class CurrencyRate
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
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $purchase;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $sale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var Cashbox
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cashbox", inversedBy="currencyRates")
     */
    private $cashboxCurrency;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="currencyRates")
     */
    private $user;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function setPurchase(float $purchase): self
    {
        $this->purchase = $purchase;
        return $this;
    }

    public function getPurchase(): ?float
    {
        return $this->purchase;
    }

    public function setSale(float $sale): self
    {
        $this->sale = $sale;
        return $this;
    }

    public function getSale(): ?float
    {
        return $this->sale;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCashboxCurrency(Cashbox $cashboxCurrency): self
    {
        $this->cashboxCurrency = $cashboxCurrency;
        return $this;
    }

    public function getCashboxCurrency(): ?Cashbox
    {
        return $this->cashboxCurrency;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
