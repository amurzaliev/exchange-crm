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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\OneToMany(targetEntity="App\Entity\PermissionGroup", mappedBy="user")
     */
    private $permissionGroups;

    /**
     * @var Currency[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Currency", mappedBy="user")
     */
    private $currencies;

    /**
     * @var Cashbox[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Cashbox", mappedBy="user")
     */
    private $cashboxes;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Staff", mappedBy="user", cascade={"persist", "remove"})
     */
    private $staff;

    /**
     * @var Staff[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Staff", mappedBy="owner")
     */
    private $staffs;

    /**
     * @var CurrencyRate[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\CurrencyRate", mappedBy="user")
     */
    private $currencyRates;

    /**
     * @var VIPClient
     *
     * @ORM\OneToMany(targetEntity="App\Entity\VIPClient", mappedBy="user")
     */
    private $vipClients;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_OWNER']);
        $this->cashboxes = new ArrayCollection();
        $this->staffs = new ArrayCollection();
        $this->currencyRates = new ArrayCollection();
        $this->currencies = new ArrayCollection();
        $this->vipClients = new ArrayCollection();
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

    public function addExchangeOffice(ExchangeOffice $exchangeOffice): self
    {
        if (!$this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->add($exchangeOffice);
            $exchangeOffice->setUser($this);
        }

        return $this;
    }

    public function removeExchangeOffice(ExchangeOffice $exchangeOffice): self
    {
        if ($this->exchangeOffices->contains($exchangeOffice)) {
            $this->exchangeOffices->removeElement($exchangeOffice);
            if ($exchangeOffice->getUser() === $this) {
                $exchangeOffice->setUser(null);
            }
        }

        return $this;
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

    public function addPermissionGroups(PermissionGroup $permissionGroup): self
    {
        if (!$this->permissionGroups->contains($permissionGroup)) {
            $this->permissionGroups->add($permissionGroup);
            $permissionGroup->setUser($this);
        }

        return $this;
    }

    public function removePermissionGroups(PermissionGroup $permissionGroup): self
    {
        if ($this->permissionGroups->contains($permissionGroup)) {
            $this->permissionGroups->removeElement($permissionGroup);
            if ($permissionGroup->getUser() === $this) {
                $permissionGroup->setUser(null);
            }
        }

        return $this;
    }

    public function setCurrencies(Collection $currencies): self
    {
        $this->currencies = $currencies;

        return $this;
    }

    public function getCurrencies(): Collection
    {
        return $this->currencies;
    }

    public function addCurrency(Currency $currency): self
    {
        if (!$this->currencies->contains($currency)) {
            $this->currencies->add($currency);
            $currency->setUser($this);
        }

        return $this;
    }

    public function removeCurrency(Currency $currency): self
    {
        if ($this->currencies->contains($currency)) {
            $this->currencies->removeElement($currency);
            if ($currency->getUser() === $this) {
                $currency->setUser(null);
            }
        }
    }

    public function addCashbox(Cashbox $cashbox): self
    {
        if (!$this->cashboxes->contains($cashbox)) {
            $this->cashboxes->add($cashbox);
            $cashbox->setUser($this);
        }

        return $this;
    }

    public function removeCashbox(Cashbox $cashbox): self
    {
        if ($this->cashboxes->contains($cashbox)) {
            $this->cashboxes->removeElement($cashbox);
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

    public function getStaffs(): Collection
    {
        return $this->staffs;
    }

    public function addStaff(Staff $staff): self
    {
        if (!$this->staffs->contains($staff)) {
            $this->staffs->add($staff);
            $staff->setOwner($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): self
    {
        if ($this->staffs->contains($staff)) {
            $this->staffs->removeElement($staff);
            if ($staff->getOwner() === $this) {
                $staff->setOwner(null);
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

    /**
     * @return Collection|VIPClient[]
     */
    public function getVipClients(): Collection
    {
        return $this->vipClients;
    }

    public function addVipClient(VIPClient $vipClient): self
    {
        if (!$this->vipClients->contains($vipClient)) {
            $this->vipClients[] = $vipClient;
            $vipClient->setUser($this);
        }

        return $this;
    }

    public function removeVipClient(VIPClient $vipClient): self
    {
        if ($this->vipClients->contains($vipClient)) {
            $this->vipClients->removeElement($vipClient);
            // set the owning side to null (unless already changed)
            if ($vipClient->getUser() === $this) {
                $vipClient->setUser(null);
            }
        }

        return $this;
    }
}
