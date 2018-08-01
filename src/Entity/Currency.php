<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
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
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $icon;

    /**
     * @Vich\UploadableField(mapping="currency_icon", fileNameProperty="icon")
     * @var File
     */
    private $imageFile;


    /**
     * @var datetime
     * @ORM\Column(type="date")
     */
    private $createAt;

    /**
     * @var datetime
     * @ORM\Column(type="date")
     */
    private $updatedAt;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="currency")
     */
    private $user;

    /**
     * @var Cashbox[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Cashbox", mappedBy="currency")
     */
    private $cashboxes;

    public function __construct()
    {
        $this->createAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->cashboxes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Currency
     */
    public function setName(string $name): Currency
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setImageFile(File $image = null): self
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * @param string $icon
     * @return Currency
     */
    public function setIcon(?string $icon): Currency
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * @param int $user
     * @return Currency
     */
    public function setUser($user): Currency
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }


    /**
     * @return DateTime
     */
    public function getCreateAt(): DateTime
    {
        return $this->createAt;
    }

    /**
     * @param DateTime $createAt
     * @return Currency
     */
    public function setCreateAt(DateTime $createAt): Currency
    {
        $this->createAt = $createAt;
        return $this;
    }

    /**
     * @return Collection|Cashbox[]
     */
    public function getCashboxes(): Collection
    {
        return $this->cashboxes;
    }

    public function addCashbox(Cashbox $cashbox): self
    {
        if (!$this->cashboxes->contains($cashbox)) {
            $this->cashboxes[] = $cashbox;
            $cashbox->setCurrency($this);
        }

        return $this;
    }

    public function removeCashbox(Cashbox $cashbox): self
    {
        if ($this->cashboxes->contains($cashbox)) {
            $this->cashboxes->removeElement($cashbox);
            // set the owning side to null (unless already changed)
            if ($cashbox->getCurrency() === $this) {
                $cashbox->setCurrency(null);
            }
        }

        return $this;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
