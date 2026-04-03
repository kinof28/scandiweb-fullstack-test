<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class TechProduct extends Product
{
  public function getType(): string
  {
    return 'tech';
  }
}
