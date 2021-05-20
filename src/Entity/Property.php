<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use App\Traits\Timestempable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=PropertyRepository::class)
 * @ORM\Table(name="property", indexes={@ORM\Index(columns={"title"}, flags={"fulltext"})})
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

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function __toString()
    {
        return $this->title;
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
}
