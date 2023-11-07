<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

// Création de la configuration de Doctrine
// On lui passe le dossier de base des entités
// On lui précise qu'on utilise les attributs pour le mapping
$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: array(__DIR__."/src"),
    isDevMode: true,
);

// Récupération des variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Configuration de la connexion à la base de données
$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => $_ENV['BD_HOST'],
    'dbname' => $_ENV['BD_NAME'],
    'user' => $_ENV['BD_USER'],
    'password' => $_ENV['BD_PASSWORD']
], $config);

// Création de l'entity manager
$entityManager = new EntityManager($connection, $config);



