<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'prices')]
class Price
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column(type: 'integer')]
  private int $id;

  #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
  private float $amount;

  #[ORM\ManyToOne(targetEntity: Currency::class)]
  #[ORM\JoinColumn(name: 'currency_label', referencedColumnName: 'label', nullable: false)]
  private Currency $currency;

  #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'prices')]
  #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
  private Product $product;

  public function getId(): int
  {
    return $this->id;
  }

  public function getAmount(): float
  {
    return (float) $this->amount;
  }

  public function setAmount(float $amount): self
  {
    $this->amount = $amount;
    return $this;
  }

  public function getCurrency(): Currency
  {
    return $this->currency;
  }

  public function setCurrency(Currency $currency): self
  {
    $this->currency = $currency;
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
