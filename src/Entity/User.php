<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\AttributeOverride;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\Column;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @AttributeOverrides({
 *      @AttributeOverride(name="email",
 *          column=@Column(
 *              nullable = true
 *          )
 *      ),
 *     @AttributeOverride(name="emailCanonical",
 *          column=@Column(
 *              nullable = true
 *          )
 *      )
 * })
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

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Staff", mappedBy="user", cascade={"persist", "remove"})
     */
    private $staff;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Staff", mappedBy="owner")
     */
    private $staffs;

    /**
     * @var CurrencyRate[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CurrencyRate", mappedBy="user")
     */
    private $currencyRates;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_OWNER']);
        $this->cashboxes = new ArrayCollection();
        $this->staffs = new ArrayCollection();
        $this->currencyRates = new ArrayCollection();
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

    public function getStaff(): ?Staff
    {
        return $this->staff;
    }

    public function setStaff(?Staff $staff): self
    {
        $this->staff = $staff;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $staff === null ? null : $this;
        if ($newUser !== $staff->getUser()) {
            $staff->setUser($newUser);
        }

        return $this;
    }

    /**
     * @return Collection|Staff[]
     */
    public function getStaffs(): Collection
    {
        return $this->staffs;
    }

    public function addSStaff(Staff $sStaff): self
    {
        if (!$this->staffs->contains($sStaff)) {
            $this->staffs[] = $sStaff;
            $sStaff->setOwner($this);
        }

        return $this;
    }

    public function removeSStaff(Staff $sStaff): self
    {
        if ($this->staffs->contains($sStaff)) {
            $this->staffs->removeElement($sStaff);
            // set the owning side to null (unless already changed)
            if ($sStaff->getOwner() === $this) {
                $sStaff->setOwner(null);
            }
        }

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
            $currencyRate->setUser($this);
        }

        return $this;
    }

    public function removeCurrencyRate(CurrencyRate $currencyRate): self
    {
        if ($this->currencyRates->contains($currencyRate)) {
            $this->currencyRates->removeElement($currencyRate);
            if ($currencyRate->getUser() === $this) {
                $currencyRate->setUser(null);
            }
        }

        return $this;
    }
}
