<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'currencies')]
class Currency
{
  #[ORM\Id]
  #[ORM\Column(type: 'string', length: 10)]
  private string $label;

  #[ORM\Column(type: 'string', length: 5)]
  private string $symbol;

  public function getLabel(): string
  {
    return $this->label;
  }

  public function setLabel(string $label): self
  {
    $this->label = $label;
    return $this;
  }

  public function getSymbol(): string
  {
    return $this->symbol;
  }

  public function setSymbol(string $symbol): self
  {
    $this->symbol = $symbol;
    return $this;
  }
}