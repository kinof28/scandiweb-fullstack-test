<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'attributes')]
class Attribute
{
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  #[ORM\Column(type: 'integer')]
  private int $database_id;

  #[ORM\Column(type: 'string', length: 100)]
  private string $id;

  #[ORM\Column(type: 'string', length: 255)]
  private string $value;

  #[ORM\Column(name: 'display_value', type: 'string', length: 255)]
  private string $displayValue;

  #[ORM\ManyToOne(targetEntity: AttributeSet::class, inversedBy: 'items')]
  #[ORM\JoinColumn(name: 'attribute_set_id', referencedColumnName: 'database_id', nullable: false, onDelete: 'CASCADE')]
  private AttributeSet $attributeSet;

  public function getDatabaseId(): int
  {
    return $this->database_id;
  }

  public function setDatabaseId(int $database_id): self
  {
    $this->database_id = $database_id;
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

  public function getValue(): string
  {
    return $this->value;
  }

  public function setValue(string $value): self
  {
    $this->value = $value;
    return $this;
  }

  public function getDisplayValue(): string
  {
    return $this->displayValue;
  }

  public function setDisplayValue(string $displayValue): self
  {
    $this->displayValue = $displayValue;
    return $this;
  }

  public function getAttributeSet(): AttributeSet
  {
    return $this->attributeSet;
  }

  public function setAttributeSet(AttributeSet $attributeSet): self
  {
    $this->attributeSet = $attributeSet;
    return $this;
  }
}