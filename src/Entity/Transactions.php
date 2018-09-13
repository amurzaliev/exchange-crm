<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionsRepository")
 */
class Transactions
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
     * @var Cashbox
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cashbox", inversedBy="TransactionsFrom")
     */
    private $cashboxFrom;

    /**
     * @var Cashbox
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cashbox", inversedBy="transctionsTo")
     */
    private $cashboxTo;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $basicType;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $typeTransfer;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=60, scale=4)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=4, nullable=true)
     */
    private $currentCourse;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

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
    private $updateAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="transactions")
     */
    private $user;

    /**
     * @var ExchangeOffice
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\ExchangeOffice", inversedBy="transactions")
     */
    private $exchangeOffice;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=60, scale=4, nullable=true)
     */
    private $nationalCurrency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VIPClient", inversedBy="transactions")
     */
    private $VIPClient;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdateAt(new \DateTime());
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCashboxFrom(): ?Cashbox
    {
        return $this->cashboxFrom;
    }

    public function setCashboxFrom(?Cashbox $cashboxFrom): self
    {
        $this->cashboxFrom = $cashboxFrom;

        return $this;
    }

    public function getCashboxTo(): ?Cashbox
    {
        return $this->cashboxTo;
    }

    public function setCashboxTo(?Cashbox $cashboxTo): self
    {
        $this->cashboxTo = $cashboxTo;

        return $this;
    }

    public function getBasicType(): ?int
    {
        return $this->basicType;
    }

    public function setBasicType(int $basicType): self
    {
        $this->basicType = $basicType;

        return $this;
    }

    public function getTypeTransfer()
    {
        return $this->typeTransfer;
    }

    public function setTypeTransfer($typeTransfer): self
    {
        $this->typeTransfer = $typeTransfer;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrentCourse()
    {
        return $this->currentCourse;
    }

    public function setCurrentCourse($currentCourse): self
    {
        $this->currentCourse = $currentCourse;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
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

    public function getNationalCurrency()
    {
        return $this->nationalCurrency;
    }

    public function setNationalCurrency($nationalCurrency): self
    {
        $this->nationalCurrency = $nationalCurrency;

        return $this;
    }

    public function getVIPClient(): ?VIPClient
    {
        return $this->VIPClient;
    }

    public function setVIPClient(?VIPClient $VIPClient): self
    {
        $this->VIPClient = $VIPClient;

        return $this;
    }
}
