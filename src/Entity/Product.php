<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message= "Veuillez saisir un titre"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     * message= "Veuillez saisir une description"
     * )
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     * message= "Veuillez saisir un prix"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     * message= "Veuillez ajouter une image"
     * )
     */
    private $picture;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(
     * message= "Veuillez ajouter une catégorie"
     * )
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseDetail::class, mappedBy="product", orphanRemoval=true)
     */
    private $purchaseDetails;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->purchaseDetails = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PurchaseDetail>
     */
    public function getPurchaseDetails(): Collection
    {
        return $this->purchaseDetails;
    }

    public function addPurchaseDetail(PurchaseDetail $purchaseDetail): self
    {
        if (!$this->purchaseDetails->contains($purchaseDetail)) {
            $this->purchaseDetails[] = $purchaseDetail;
            $purchaseDetail->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseDetail(PurchaseDetail $purchaseDetail): self
    {
        if ($this->purchaseDetails->removeElement($purchaseDetail)) {
            // set the owning side to null (unless already changed)
            if ($purchaseDetail->getProduct() === $this) {
                $purchaseDetail->setProduct(null);
            }
        }

        return $this;
    }
}