<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PermissionGroupRepository")
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
    private $create_at;

    /**
     * @var datetime
     * @ORM\Column(type="date")
     */
    private $updated_at;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User", inversedBy="currency")
     */
    private $user;


    public function __construct()
    {
        $this->create_at = new DateTime();
        $this->updated_at = new DateTime();
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
            $this->updated_at = new DateTime('now');
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
    public function setIcon(string $icon): Currency
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
        return $this->create_at;
    }

    /**
     * @param DateTime $create_at
     * @return Currency
     */
    public function setCreateAt(DateTime $create_at): Currency
    {
        $this->create_at = $create_at;
        return $this;
    }

}
