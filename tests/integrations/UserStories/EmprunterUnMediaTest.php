<?php

use App\Entites\Adherent;
use App\Entites\Media;
use App\Entites\StatutMedia;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\EmprunterUnMedia\EmprunterUnMedia;
use App\UserStories\EmprunterUnMedia\EmprunterUnMediaRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Faker\Factory;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class EmprunterUnMediaTest extends TestCase{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
    protected GenerateurNumeroEmprunt $generateurNumeroEmprunt;
    protected function setUp(): void
    {
        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . '/../../../src/'],
            true
        );
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création de l'entity manager
        $this->entityManager = new EntityManager($connection, $config);
        $this->validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();
        $this->generateurNumeroEmprunt = new GenerateurNumeroEmprunt();


        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
        $faker = Factory::create("fr_FR");
        for ($i = 0; $i < 10; $i++) {
            $creerMagazineRequete = new CreerMagazineRequete($faker->sentence(3), $faker->randomNumber(4, true), ($faker->dateTime())->format('d/m/Y'));
            $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
            $creerMagazine = new CreerMagazine($this->entityManager, $validator);
            $creerMagazine->execute($creerMagazineRequete);
        }

        for ($i = 0; $i < 10; $i++) {
            $creerLivreRequete = new CreerLivreRequete($faker->sentence(3), $faker->isbn13(), $faker->name(), $faker->randomNumber(3));
            $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
            $creerLivre = new CreerLivre($this->entityManager, $this->validator);
            $creerLivre->execute($creerLivreRequete);
        }

        for ($i = 0; $i < 10; $i++) {
            $creerAdherentRequete = new CreerAdherentRequete($faker->firstName(), $faker->lastName(), $faker->freeEmail());
            $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent,$this->validator);
            $creerAdherent->execute($creerAdherentRequete);
        }
    }

    #[test]
    public function emprunterMedia_ValeursCorrectes_Vrai(){
        $emprunterMedia = new EmprunterUnMedia($this->entityManager, $this->validator, $this->generateurNumeroEmprunt);
        $numeroAdherent = ($this->entityManager->getRepository(Adherent::class)->findOneBy(['id' => 1]))->getNumeroAdherent();
        $media = $this->entityManager->getRepository(Media::class)->findOneBy(['id' => 1]);
        $requete = new EmprunterUnMediaRequete($media->getId(), $numeroAdherent);

        $emprunterMedia->execute($requete);
        $this->assertEquals(StatutMedia::STATUT_EMPRUNTE, $media->getStatut());
    }

}