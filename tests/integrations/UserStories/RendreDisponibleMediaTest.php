<?php

use App\Entites\Media;
use App\Entites\StatutMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMedia;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMediaRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RendreDisponibleMediaTest extends TestCase{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
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

        // Création de l'entity manager
        $this->entityManager = new EntityManager($connection, $config);
        $this->validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();


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
    public function rendreDisponible_ValeursCorrectes_Vrai(){
        $requete = new RendreDisponibleMediaRequete(9);
        $rendreDisponible = new RendreDisponibleMedia($this->entityManager, $this->validator);

        $rendreDisponible->execute($requete);
        $repository = $this->entityManager->getRepository(Media::class);
        $media = $repository->findOneBy(['id' => $requete->id]);
        $this->assertNotNull($media);
        $this->assertEquals(StatutMedia::STATUT_DISPONIBLE, $media->getStatut());
    }

    #[test]
    public function rendreDisponible_StatutDifferentDeNouveau_Exception(){
        $requete = new RendreDisponibleMediaRequete(9);
        $rendreDisponible = new RendreDisponibleMedia($this->entityManager, $this->validator);
        $repository = $this->entityManager->getRepository(Media::class);
        $media = $repository->findOneBy(['id' => $requete->id]);
        $media->setStatut(StatutMedia::STATUT_DISPONIBLE);
        $this->assertNotNull($media);
        $this->assertNotEquals(StatutMedia::STATUT_NOUVEAU, $media->getStatut());
        $rendreDisponible->execute($requete);
    }
}