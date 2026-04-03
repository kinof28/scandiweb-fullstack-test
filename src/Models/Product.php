<?php

namespace App\Models;

use App\Repositories\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
  'tech' => TechProduct::class,
  'clothes' => ClothesProduct::class,
  'all' => AllProduct::class,
])]
abstract class Product
{
  #[ORM\Id]
  #[ORM\Column(type: 'string', length: 255)]
  private string $id;

  #[ORM\Column(type: 'string', length: 255)]
  private string $name;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  private ?string $brand = null;

  #[ORM\Column(type: 'text', nullable: true)]
  private ?string $description = null;

  #[ORM\Column(name: 'in_stock', type: 'boolean', options: ['default' => true])]
  private bool $inStock = true;

  // Stored as JSON array of URL strings e.g. ["url1", "url2"]
  #[ORM\Column(type: 'json')]
  private array $gallery = [];

  // Category is stored as a plain string (the name of the Category),
  #[ORM\Column(name: 'category', type: 'string', length: 150)]
  private string $category;

  #[ORM\OneToMany(targetEntity: Price::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
  private Collection $prices;

  #[ORM\OneToMany(targetEntity: AttributeSet::class, mappedBy: 'product', cascade: ['persist', 'remove'])]
  private Collection $attributes;

  public function __construct()
  {
    $this->prices = new ArrayCollection();
    $this->attributes = new ArrayCollection();
  }

  abstract public function getType(): string;

  public function getId(): string
  {
    return $this->id;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getBrand(): ?string
  {
    return $this->brand;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function isInStock(): bool
  {
    return $this->inStock;
  }

  public function getGallery(): array
  {
    return $this->gallery;
  }

  public function getCategory(): string
  {
    return $this->category;
  }

  public function getPrices(): Collection
  {
    return $this->prices;
  }

  public function getAttributes(): Collection
  {
    return $this->attributes;
  }

  public function setId(string $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  public function setBrand(?string $brand): self
  {
    $this->brand = $brand;
    return $this;
  }

  public function setDescription(?string $description): self
  {
    $this->description = $description;
    return $this;
  }

  public function setInStock(bool $inStock): self
  {
    $this->inStock = $inStock;
    return $this;
  }

  public function setGallery(array $gallery): self
  {
    $this->gallery = $gallery;
    return $this;
  }

  public function setCategory(string $category): self
  {
    $this->category = $category;
    return $this;
  }

  public function addPrice(Price $price): self
  {
    if (!$this->prices->contains($price)) {
      $this->prices->add($price);
      $price->setProduct($this);
    }
    return $this;
  }

  public function addAttributeSet(AttributeSet $attributeSet): self
  {
    if (!$this->attributes->contains($attributeSet)) {
      $this->attributes->add($attributeSet);
      $attributeSet->setProduct($this);
    }
    return $this;
  }
}