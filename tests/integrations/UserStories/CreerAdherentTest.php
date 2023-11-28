<?php


use App\Entites\Adherent;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerAdherentTest extends TestCase{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
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

        // Création de l'entity manager et le générateur
        $this->entityManager = new EntityManager($connection, $config);
        $this->generateur = new GenerateurNumeroAdherent();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    #[test]
    public function creerAdherent_ValeursCorrectes_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', 'thomas.gioana@test.fr');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);

        $creerAdherent->execute($requete);
        $repository = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repository->findOneBy(['email' => $requete->email]);
        $this->assertNotNull($adherent);
        $this->assertEquals($adherent->getEmail(),$requete->email);
    }

    #[test]
    public function creerAdherent_PrenomNonRenseigne_Vrai(){
        $requete = new CreerAdherentRequete('', 'Gioana', 'thomas.gioana@tes.fr');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le prénom est obligatoire.");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_NomNonRenseigne_Exception(){
        $requete = new CreerAdherentRequete('Thomas', '', 'thomas.gioana@tes.fr');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le nom est obligatoire.");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonRenseigne_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', '');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail est obligatoire.");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonValableSansArobase_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', 'thomas.gioanatest.fr');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail n'est pas dans un format valide");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonValableSansPoint_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', 'thomas.gioana@test');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail n'est pas dans un format valide");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonValableAvecEspace_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', 'thomas.gioana@test.fr ');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail n'est pas dans un format valide");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonValablePremierePartieManquante_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', '@test.fr');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail n'est pas dans un format valide");
        $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonValableDeuxiemePartieManquante_Vrai(){
        $requete = new CreerAdherentRequete('Thomas', 'Gioana', 'thomas.gioana@');
        $generateur = new GenerateurNumeroAdherent();
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerAdherent = new CreerAdherent($this->entityManager, $generateur, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail n'est pas dans un format valide");
        $creerAdherent->execute($requete);
    }
}