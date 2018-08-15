<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CurrencyRepository")
 * @Vich\Uploadable
 */
class Currency
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
     * @ORM\Column(type="string", length=255)
     *
     */
    private $icon;

    /**
     * @var File
     *
     * @Vich\UploadableField(mapping="currency_icon", fileNameProperty="icon")

     */
    private $imageFile;


    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="currencies")
     */
    private $user;

    /**
     * @var Cashbox[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Cashbox", mappedBy="currency")
     */
    private $cashboxes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iso;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $symbolDesignation;


    /**
     * @ORM\Column(type="integer")
     */
    private $decimals;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $decimalSeparator;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $thousandSeparator;

    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->cashboxes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setImageFile(File $image = null): self
    {
        $this->imageFile = $image;

        $this->setUpdatedAt(new \DateTime());

        if ($image instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime('now'));
        }

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }


    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

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
            $cashbox->setCurrency($this);
        }

        return $this;
    }

    public function removeCashbox(Cashbox $cashbox): self
    {
        if ($this->cashboxes->contains($cashbox)) {
            $this->cashboxes->removeElement($cashbox);
            if ($cashbox->getCurrency() === $this) {
                $cashbox->setCurrency(null);
            }
        }

        return $this;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getIso(): ?string
    {
        return $this->iso;
    }

    public function setIso(string $iso): self
    {
        $this->iso = $iso;

        return $this;
    }

    public function getSymbolDesignation(): ?string
    {
        return $this->symbolDesignation;
    }

    public function setSymbolDesignation(string $symbolDesignation): self
    {
        $this->symbolDesignation = $symbolDesignation;

        return $this;
    }

    public function getDecimals(): ?int
    {
        return $this->decimals;
    }

    public function setDecimals(int $decimals): self
    {
        $this->decimals = $decimals;

        return $this;
    }

    public function getDecimalSeparator(): ?string
    {
        return $this->decimalSeparator;
    }

    public function setDecimalSeparator(string $decimalSeparator): self
    {
        $this->decimalSeparator = $decimalSeparator;

        return $this;
    }

    public function getThousandSeparator(): ?string
    {
        return $this->thousandSeparator;
    }

    public function setThousandSeparator(string $thousandSeparator): self
    {
        $this->thousandSeparator = $thousandSeparator;

        return $this;
    }
}
