<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use App\Traits\Timestempable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Property
{
    
    use Timestempable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string|null
     */
    private $filename;


    /**
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="filename")
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="properties")
     */
    private $categories;

    /**
     * @ORM\ManyToOne(targetEntity=Quarter::class, inversedBy="properties")
     */
    private $quarter;

    /**
     * @ORM\ManyToOne(targetEntity=Owner::class, inversedBy="properties")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="property", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $area;

    /**
     * @ORM\Column(type="integer")
     */
    private $room;

    /**
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;


    /**
     * @ORM\Column(type="boolean")
     */
    private $featured;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="properties", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="properties")
     */
    private $state;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, inversedBy="properties")
     */
    private $types;

    
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->type = new ArrayCollection();
        $this->types = new ArrayCollection();
    }
    

    public function getId(): ?int
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @return string|null
     */
    public function setFilename(?string $filename):self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if (null !==$imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }


    public function getQuarter(): ?Quarter
    {
        return $this->quarter;
    }

    public function setQuarter(?Quarter $quarter): self
    {
        $this->quarter = $quarter;

        return $this;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function setOwner(?Owner $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProperty($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProperty() === $this) {
                $image->setProperty(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getArea(): ?int
    {
        return $this->area;
    }

    public function setArea(?int $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getRoom(): ?int
    {
        return $this->room;
    }

    public function setRoom(int $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished(bool $published)
    {
        $this->published = $published;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }


    
    public function __toString()
    {
        return $this->title;
    }

    public function getFeatured()
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured)
    {
        $this->featured = $featured;

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

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }
}
