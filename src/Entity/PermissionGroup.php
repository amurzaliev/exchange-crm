<?php

namespace App\Entity;

use App\Libs\Enumeration\PermissionGroupEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermissionGroupRepository")
 */
class PermissionGroup
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
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $alias;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="permissionGroups")
     */
    private $user;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $create_personal;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $edit_personal;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $view_personal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Staff", mappedBy="permissionGroup")
     */
    private $staff;

    public function __construct()
    {
        $this->staff = new ArrayCollection();
    }

    public function getId():?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
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

    public function setCreatePersonal(bool $create_personal): self
    {
        $this->create_personal = $create_personal;

        return $this;
    }

    public function isCreatePersonal(): ?bool
    {
        return $this->create_personal;
    }

    public function setEditPersonal(bool $edit_personal): self
    {
        $this->edit_personal = $edit_personal;

        return $this;
    }

    public function isEditPersonal(): ?bool
    {
        return $this->edit_personal;
    }

    public function setViewPersonal(bool $view_personal): self
    {
        $this->view_personal = $view_personal;

        return $this;
    }

    public function isViewPersonal(): ?bool
    {
        return $this->view_personal;
    }

    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Staff $staff): self
    {
        if (!$this->staff->contains($staff)) {
            $this->staff->add($staff);
            $staff->setPermissionGroup($this);
        }

        return $this;
    }

    public function removeStaff(Staff $staff): self
    {
        if ($this->staff->contains($staff)) {
            $this->staff->removeElement($staff);
            if ($staff->getPermissionGroup() === $this) {
                $staff->setPermissionGroup(null);
            }
        }

        return $this;
    }

    public function toArrayPermissions()
    {
        $array = [
            'create' => [
                'title' => PermissionGroupEnum::CREATE_PERSONAL,
                'status' => $this->create_personal
            ],
            'edit' =>[
                'title' => PermissionGroupEnum::EDIT_PERSONAL,
                'status' => $this->edit_personal
            ],
            'view' =>[
                'title' => PermissionGroupEnum::VIEW_PERSONAL,
                'status' => $this->view_personal
            ]
        ];
        return $array;
    }
}
