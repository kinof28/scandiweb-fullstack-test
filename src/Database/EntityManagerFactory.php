<?php

namespace App\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;

class EntityManagerFactory
{
  /**
   * @param array<string, mixed> $config  Contents of config/doctrine.php
   */
  public static function create(array $config): EntityManagerInterface
  {
    $ormConfig = new Configuration();

    // --- Mapping driver --------------------------------------------------
    $ormConfig->setMetadataDriverImpl(
      new AttributeDriver($config['mapping']['paths'])
    );

    // --- Proxies --------------------------------------------------------
    /*  $ormConfig->setProxyDir($config['proxy']['dir']);
     $ormConfig->setProxyNamespace($config['proxy']['namespace']);
     $ormConfig->setAutoGenerateProxyClasses($config['proxy']['auto_generate']);
  */
    // --- Connection & EntityManager -------------------------------------
    $connection = DriverManager::getConnection($config['connection'], $ormConfig);

    return new EntityManager($connection, $ormConfig);
  }
}