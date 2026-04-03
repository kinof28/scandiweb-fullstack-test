<?php

namespace App\Models;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ClothesProduct extends Product
{
    public function getType(): string
    {
        return 'clothes';
    }
}