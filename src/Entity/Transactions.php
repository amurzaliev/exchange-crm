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
    private $сashboxFrom;

    /**
     * @var Cashbox
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Cashbox", inversedBy="transctionsTo")
     */
    private $сashboxTo;

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
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", precision=10, scale=4)
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
     * @ORM\Column(type="decimal", precision=10, scale=4)
     */
    private $nationalCurrency;

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
        return $this->сashboxFrom;
    }

    public function setCashboxFrom(?Cashbox $сashboxFrom): self
    {
        $this->сashboxFrom = $сashboxFrom;

        return $this;
    }

    public function getсashboxTo(): ?Cashbox
    {
        return $this->сashboxTo;
    }

    public function setсashboxTo(?Cashbox $сashboxTo): self
    {
        $this->сashboxTo = $сashboxTo;

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
}