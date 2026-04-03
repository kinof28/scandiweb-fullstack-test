<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AllProduct extends Product
{

  public function getType(): string
  {
    return 'all';
  }
}