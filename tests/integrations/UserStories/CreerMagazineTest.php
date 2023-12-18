<?php


use App\Entites\Magazine;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerMagazineTest extends TestCase{
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

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function creerMagazine_ValeursCorrectes_Vrai(){
        $requete = new CreerMagazineRequete("Titre", "572", "21/11/2023");
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerMagazine = new CreerMagazine($this->entityManager, $validator);

        $creerMagazine->execute($requete);
        $repository = $this->entityManager->getRepository(Magazine::class);
        $magazine = $repository->findOneBy(['numeroMagazine' => $requete->numeroMagazine]);
        $this->assertNotNull($magazine);
        $this->assertEquals($magazine->getNumeroMagazine(),$requete->numeroMagazine);
    }

    #[test]
    public function creerMagazine_TitreNonRenseigne_Exception(){
        $requete = new CreerMagazineRequete("", "572", "21/11/2023");
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerMagazine = new CreerMagazine($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le titre est obligatoire");
        $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_NumeroMagazineNonRenseigne_Exception(){
        $requete = new CreerMagazineRequete("Titre", "", "21/11/2023");
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerMagazine = new CreerMagazine($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le numéro de magazine est obligatoire");
        $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_NumeroMagazineDejaExistant_Exception(){
        $requete = new CreerMagazineRequete("Titre", "5", "21/11/2023");
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerMagazine = new CreerMagazine($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le numéro de magazine est déjà lié à un autre magazine");
        $creerMagazine->execute($requete);
        $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_DateParutionNonRenseigne_Exception(){
        $requete = new CreerMagazineRequete("Titre", "572", "");
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerMagazine = new CreerMagazine($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("La date de parution est obligatoire");
        $creerMagazine->execute($requete);
    }
}