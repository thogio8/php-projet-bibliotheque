<?php

namespace Tests\integrations\UserStories;

use App\Entites\Media;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\ListeNouveauxMedias\ListeNouveauxMedias;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ValidatorBuilder;

require "vendor/autoload.php";

class ListeNouveauxMediasTest extends TestCase{
    protected EntityManagerInterface $entityManager;
    protected function setUp(): void
    {
        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__.'/../../../src/'],
            true
        );
        $connection = DriverManager::getConnection([
            'driver'=> 'pdo_sqlite',
            'path'=>':memory:'
        ], $config);

        // Création de l'entity manager et le générateur
        $this->entityManager = new EntityManager($connection, $config);

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);

        $faker = Factory::create("fr_FR");
        for($i = 0; $i < 10; $i++){
            $creerMagazineRequete = new CreerMagazineRequete($faker->sentence(3), $faker->randomNumber(4, true), ($faker->dateTime())->format('d/m/Y'));
            $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
            $creerMagazine = new CreerMagazine($this->entityManager, $validator);
            $creerMagazine->execute($creerMagazineRequete);
        }

        for($i = 0; $i < 10; $i++){
            $creerLivreRequete = new CreerLivreRequete($faker->sentence(3), $faker->isbn13(), $faker->name(), $faker->randomNumber(3));
            $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
            $creerLivre = new CreerLivre($this->entityManager, $validator);
            $creerLivre->execute($creerLivreRequete);
        }
    }

    #[test]
    public function listeNouveauxMedias_DateCreationDecroissante_Vrai(){
        $listeMedia = (new ListeNouveauxMedias($this->entityManager))->execute();
        foreach ($listeMedia as $media){
            $listeDateCreation[] = $media['dateCreation'];
        }
        $dateCreation = $listeDateCreation;
        usort($dateCreation, function ($element1, $element2) {
            $datetime1 = strtotime(str_replace('/', '-', $element1));
            $datetime2 = strtotime(str_replace('/', '-', $element2));
            return $datetime2 - $datetime1;
        });
        $this->assertEquals($dateCreation, $listeDateCreation);
    }
}