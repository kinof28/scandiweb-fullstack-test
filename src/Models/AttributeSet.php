<?php
namespace App\Models;
abstract class AttributeSet
{
  public function __construct(string $name, array $items = [])
  {
    $this->name = $name;
    $this->id = $name;
    $this->items = $items;
  }
  public string $id;
  public string $name;
  protected string $type;

  /**
   * @var Attribute[]
   */
  public array $items = [];
}