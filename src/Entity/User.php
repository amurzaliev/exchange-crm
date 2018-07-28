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

    /**
     * @var PermissionGroup[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\PermissionGroup", mappedBy="permissionGroup")
     */
    private $permissionGroups;

    /**
     * @var integer
     * @ORM\OneToMany(targetEntity="Currency", mappedBy="user")
     */
    private $currency;

    /**
     * @var Cashbox[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Cashbox", mappedBy="user")
     */
    private $cashboxes;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_OWNER']);
        $this->cashboxes = new ArrayCollection();
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

    public function setPermissionGroups(Collection $permissionGroups)
    {
        $this->permissionGroups = $permissionGroups;
        return $this;
    }

    public function getPermissionGroups(): Collection
    {
        return $this->permissionGroups;
    }

    public function addPermissionGroups(PermissionGroup $permissionGroup)
    {
        if (!$this->permissionGroups->contains($permissionGroup)) {
            $this->permissionGroups->add($permissionGroup);
        }
    }

    public function removePermissionGroups(PermissionGroup $permissionGroup)
    {
        if (!$this->permissionGroups->contains($permissionGroup)) {
            $this->permissionGroups->removeElement($permissionGroup);
        }
    }

    /**
     * @param int $currency
     * @return User
     */
    public function setCurrency(int $currency): User
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return int
     */
    public function getCurrency(): int
    {
        return $this->currency;
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
            $cashbox->setUser($this);
        }

        return $this;
    }

    public function removeCashbox(Cashbox $cashbox): self
    {
        if ($this->cashboxes->contains($cashbox)) {
            $this->cashboxes->removeElement($cashbox);
            // set the owning side to null (unless already changed)
            if ($cashbox->getUser() === $this) {
                $cashbox->setUser(null);
            }
        }

        return $this;
    }
}
