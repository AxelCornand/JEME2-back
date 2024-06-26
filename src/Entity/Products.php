<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductsRepository::class)
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_products"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"get_products"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_products"})
     */
    private $poster;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_products"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_products"})
     */
    private $matiere;

    /**
     * @ORM\Column(type="float")
     * @Groups({"get_products"})
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_products"})
     */
    private $news;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_products"})
     */
    private $stock;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_products"})
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=SubCategory::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_products"})
     */
    private $subcategory;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_products"})
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_products"})
     */
    private $promotion;

    /**
     * @ORM\ManyToMany(targetEntity=Cart::class, mappedBy="products")
     */
    private $carts;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMatiere(): ?string
    {
        return $this->matiere;
    }

    public function setMatiere(?string $matiere): self
    {
        $this->matiere = $matiere;

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

    public function isNews(): ?bool
    {
        return $this->news;
    }

    public function setNews(bool $news): self
    {
        $this->news = $news;

        return $this;
    }

    public function isStock(): ?bool
    {
        return $this->stock;
    }

    public function setStock(bool $stock): self
    {
        $this->stock = $stock;

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

    public function getSubcategory(): ?SubCategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?SubCategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isPromotion(): ?bool
    {
        return $this->promotion;
    }

    public function setPromotion(bool $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * @return Collection<int, Cart>
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->addProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            $cart->removeProduct($this);
        }

        return $this;
    }

}
