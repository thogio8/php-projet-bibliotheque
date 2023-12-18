<?php


use App\Entites\Livre;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
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

class CreerLivreTest extends TestCase{
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
    public function creerLivre_ValeursCorrectes_Vrai(){
        $requete = new CreerLivreRequete("Titre", "978-2-07-061275-8", "Moi", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);

        $creerLivre->execute($requete);
        $repository = $this->entityManager->getRepository(Livre::class);
        $livre = $repository->findOneBy(['isbn' => $requete->isbn]);
        $this->assertNotNull($livre);
        $this->assertEquals($livre->getIsbn(),$requete->isbn);
    }

    #[test]
    public function creerLivre_ISBNDejaExistant_Exception(){
        $requete = new CreerLivreRequete("Titre", "978-2-07-061275-8", "Moi", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("L'ISBN appartient à un autre livre");
        $creerLivre->execute($requete);
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonValable_Exception(){
        $requete = new CreerLivreRequete("Titre", "978-2-07-061275", "Moi", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("L'ISBN n'est pas valide");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_TitreNonRenseigne_Exception(){
        $requete = new CreerLivreRequete("", "978-2-07-061275-8", "Moi", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le titre est obligatoire");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonRenseigne_Exception(){
        $requete = new CreerLivreRequete("Titre", "", "Moi", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("L'ISBN est obligatoire");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_AuteurNonRenseigne_Exception(){
        $requete = new CreerLivreRequete("Titre", "978-2-07-061275-8", "", 100);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("L'auteur est obligatoire");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NbPagesEgalA0_Exception(){
        $requete = new CreerLivreRequete("Titre", "978-2-07-061275-8", "Moi", 0);
        $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        $creerLivre = new CreerLivre($this->entityManager, $validator);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le nombre de pages ne peut pas être inférieur ou égal 0");
        $creerLivre->execute($requete);
    }
}