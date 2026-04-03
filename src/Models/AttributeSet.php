<?php

namespace App\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attribute_sets')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
  'text' => TextAttributeSet::class,
  'swatch' => SwatchAttributeSet::class,
])]
abstract class AttributeSet
{
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  #[ORM\Column(type: 'integer')]
  private int $database_id;

  #[ORM\Column(type: 'string', length: 100)]
  private string $id;

  #[ORM\Column(type: 'string', length: 150)]
  private string $name;

  // 'type' column is managed by Doctrine as the discriminator — no property needed.

  #[ORM\OneToMany(targetEntity: Attribute::class, mappedBy: 'attributeSet', cascade: ['persist', 'remove'])]
  private Collection $items;

  #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'attributes')]
  #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
  private Product $product;

  public function __construct()
  {
    $this->items = new ArrayCollection();
  }

  public function getDatabaseId(): int
  {
    return $this->database_id;
  }

  public function setDatabaseId(int $db_id): self
  {
    $this->database_id = $db_id;
    return $this;
  }
  public function getId(): string
  {
    return $this->id;
  }

  public function setId(string $id): self
  {
    $this->id = $id;
    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;
    return $this;
  }

  abstract public function getType(): string;

  public function getItems(): Collection
  {
    return $this->items;
  }

  public function addItem(Attribute $attribute): self
  {
    if (!$this->items->contains($attribute)) {
      $this->items->add($attribute);
      $attribute->setAttributeSet($this);
    }
    return $this;
  }

  public function getProduct(): Product
  {
    return $this->product;
  }

  public function setProduct(Product $product): self
  {
    $this->product = $product;
    return $this;
  }
}