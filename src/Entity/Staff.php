<?php

namespace App\Entity;

use App\Security\Voter\CashboxVoter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StaffRepository")
 */
class Staff
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
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="staff", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="staffs")
     */
    private $owner;

    /**
     * @var PermissionGroup
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\PermissionGroup", inversedBy="staff")
     */
    private $permissionGroup;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $position;

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
     * @var ExchangeOffice[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\ExchangeOffice", mappedBy="staffs", cascade={"persist"})
     */
    private $exchangeOffices;

    public function __construct()
    {
        $this->exchangeOffices = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getPermissionGroup(): ?PermissionGroup
    {
        return $this->permissionGroup;
    }

    public function setPermissionGroup(PermissionGroup $permissionGroup): self
    {
        $this->permissionGroup = $permissionGroup;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

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

    public function getExchangeOffices(): Collection
    {
        return $this->exchangeOffices;
    }

    public function addExchangeOffice(ExchangeOffice $exchangeOffice): self
    {
        if (!$this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->add($exchangeOffice);
            $exchangeOffice->addStaff($this);
        }

        return $this;
    }

    public function removeExchangeOffice(ExchangeOffice $exchangeOffice): self
    {
        if ($this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->removeElement($exchangeOffice);
            $exchangeOffice->removeStaff($this);
        }

        return $this;
    }

    public function hasExchangeOffice(ExchangeOffice $exchangeOffice): bool
    {
        return $this->exchangeOffices->contains($exchangeOffice);
    }
    public function hasCashbox(Cashbox $cashbox): bool
    {
        return $this->exchangeOffices->contains($cashbox->getExchangeOffice());
    }

    public function toArray()
    {
        $data = [
            "staf_id" => $this->getId(),
            "fullname" => $this->user->getFullName(),
            "username" => $this->user->getUsername(),
            "enabled" => $this->user->isEnabled(),
            "group" => $this->getPermissionGroup() ? $this->getPermissionGroup()->getId() : null,
            "position" => $this->getPosition(),
        ];

        return $data;
    }
}
