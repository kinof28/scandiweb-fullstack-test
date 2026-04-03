<?php

namespace Database\Seeds;

use App\Models\AllProduct;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Category;
use App\Models\ClothesProduct;
use App\Models\Currency;
use App\Models\Price;
use App\Models\Product;
use App\Models\SwatchAttributeSet;
use App\Models\TechProduct;
use App\Models\TextAttributeSet;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseSeeder
{
  private int $categoriesCount = 0;
  private int $currenciesCount = 0;
  private int $productsCount = 0;
  public function __construct(
    private readonly EntityManagerInterface $em
  ) {
  }
  public function run(string $jsonPath): void
  {
    $data = $this->loadJson($jsonPath);

    $this->seedCategories($data['data']['categories'] ?? []);
    $this->seedCurrencies($data['data']['products'] ?? []);
    $this->seedProducts($data['data']['products'] ?? []);
    $this->em->flush();
    echo ("
      Categories: {$this->categoriesCount}\n
      Currencies: {$this->currenciesCount}\n
      Products: {$this->productsCount}\n");
  }

  private function seedCurrencies(array $products): void
  {
    $seen = [];

    foreach ($products as $productData) {
      foreach ($productData['prices'] ?? [] as $priceData) {
        $label = $priceData['currency']['label'];
        $symbol = $priceData['currency']['symbol'];

        if (isset($seen[$label])) {
          continue;
        }

        // Skip if already persisted
        $existing = $this->em
          ->getRepository(Currency::class)
          ->find($label);

        if ($existing) {
          $seen[$label] = $existing;
          continue;
        }

        $currency = (new Currency())
          ->setLabel($label)
          ->setSymbol($symbol);

        $this->em->persist($currency);
        $this->currenciesCount++;
        $seen[$label] = $currency;
      }
    }

    // Flush currencies first so they are available as FK targets.
    $this->em->flush();
  }

  private function seedCategories(array $categories): void
  {
    foreach ($categories as $categoryData) {
      $name = is_array($categoryData)
        ? $categoryData['name']
        : (string) $categoryData;

      $existing = $this->em
        ->getRepository(Category::class)
        ->find($name);

      if ($existing) {
        continue;
      }

      $category = (new Category())->setName($name);
      $this->em->persist($category);
      $this->categoriesCount++;
    }

    $this->em->flush();
  }

  private function seedProducts(array $products): void
  {
    foreach ($products as $productData) {
      $existing = $this->em
        ->getRepository(Product::class)
        ->find($productData['id']);

      if ($existing) {
        continue;
      }
      $product = $this->buildProduct($productData);
      $this->seedPrices($productData['prices'] ?? [], $product);
      $this->seedAttributeSets($productData['attributes'] ?? [], $product);

      $this->em->persist($product);
      $this->productsCount++;
    }
  }

  private function buildProduct(array $data): Product
  {
    $type = strtolower($data['category'] ?? 'all');

    $product = match ($type) {
      'tech' => new TechProduct(),
      'clothes' => new ClothesProduct(),
      default => new AllProduct(),
    };

    $product
      ->setId($data['id'])
      ->setName($data['name'])
      ->setBrand($data['brand'] ?? null)
      ->setDescription($data['description'] ?? null)
      ->setInStock((bool) ($data['inStock'] ?? true))
      ->setGallery($data['gallery'] ?? [])
      ->setCategory($data['category']);

    return $product;
  }

  private function seedPrices(array $prices, Product $product): void
  {
    foreach ($prices as $priceData) {
      $label = $priceData['currency']['label'];
      $currency = $this->em->getRepository(Currency::class)->find($label);

      if (!$currency) {
        throw new \RuntimeException("Currency '{$label}' not found. Run seedCurrencies() first.");
      }

      $price = (new Price())
        ->setAmount((float) $priceData['amount'])
        ->setCurrency($currency);

      $product->addPrice($price);
      $this->em->persist($price);
    }
  }

  private function seedAttributeSets(array $attributeSets, Product $product): void
  {
    foreach ($attributeSets as $setData) {
      $attributeSet = $this->buildAttributeSet($setData);

      foreach ($setData['items'] ?? [] as $itemData) {

        $attribute = (new Attribute())
          ->setId($itemData['id'])
          ->setValue($itemData['value'])
          ->setDisplayValue($itemData['displayValue']);
        $attributeSet->addItem($attribute);
        $this->em->persist($attribute);
      }

      $product->addAttributeSet($attributeSet);
      $this->em->persist($attributeSet);
    }
  }

  private function buildAttributeSet(array $data): AttributeSet
  {
    $type = strtolower($data['type'] ?? 'text');

    $set = match ($type) {
      'swatch' => new SwatchAttributeSet(),
      default => new TextAttributeSet(),
    };

    $set->setId($data['id'])->setName($data['name']);

    return $set;
  }

  private function loadJson(string $path): array
  {
    if (!file_exists($path)) {
      throw new \RuntimeException("Seed file not found: {$path}");
    }

    $decoded = json_decode(file_get_contents($path), associative: true, flags: JSON_THROW_ON_ERROR);

    if (!is_array($decoded)) {
      throw new \RuntimeException("Seed file must contain a JSON object.");
    }

    return $decoded;
  }
}