<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("title")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Length(min=3, max=50)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=15)
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups="insertion")
     * @Assert\Image(maxSize = "2M", minWidth="200", minHeight= "200")
     * @var object
     */
    private $image;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="products")
     * @var User owner
     * 
     */
    
    private $owner;
    
    public function getOwner(): User {
        return $this->owner;
    }

    public function setOwner(User $owner) {
        $this->owner = $owner;
        return $this;
    }

    
    /**
     *@ORM\ManyToMany(targetEntity="Tag", inversedBy="products", cascade="persist")
     * @var collection 
     */
    
    private $tags;
    
    /**
     *
     * @ORM\OneToOne(targetEntity="Loan", mappedBy="product")
     * @var Loan
     */
    
    private $loan;
    
    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
    
    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
        return $this;
    }
    
    public function getTags() {
        return $this->tags;
    }

    public function addTag($tag)
    {
        if($this->tags->contains($tag))
        {
            return;
        }
        $this->tags->add($tag);
        $tag->getProducts()->add($this);
    }

    public function setTags(Collection $tags) {
        $this->tags = $tags;
        return $this;
    }


}
