<?php

namespace App\Models;
abstract class Product
{
  public function __construct(string $id, string $name, bool $inStock = false, string $description = "", array $gallery = [], array $attributes = [], array $prices = [], string $brand = "")
  {
    $this->id = $id;
    $this->name = $name;
    $this->inStock = $inStock;
    $this->description = $description;
    $this->gallery = $gallery;
    $this->attributes = $attributes;
    $this->prices = $prices;
    $this->brand = $brand;
  }
  public string $id;
  public string $name;
  public bool $inStock = false;
  public string $description;
  /** @var string[] */
  public array $gallery = [];
  protected Category $category;


  /** @var AttributeSet[] */

  public array $attributes = [];
  /** @var Price[] */
  public array $prices = [];
  public string $brand;
}