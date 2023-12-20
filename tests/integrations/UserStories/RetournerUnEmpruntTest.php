<?php

namespace Tests\integrations\UserStories;

use App\Entites\Adherent;
use App\Entites\Emprunt;
use App\Entites\Livre;
use App\Entites\Magazine;
use App\Entites\Media;
use App\Entites\StatutMedia;
use App\Services\GenerateurNumeroAdherent;
use App\Services\GenerateurNumeroEmprunt;
use App\UserStories\RetournerUnEmprunt\RetournerUnEmprunt;
use App\UserStories\RetournerUnEmprunt\RetournerUnEmpruntRequete;
use DateTime;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\MissingMappingDriverImplementation;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Exception;
use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RetournerUnEmpruntTest extends TestCase{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected GenerateurNumeroEmprunt $generateurNumeroEmprunt;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws MissingMappingDriverImplementation
     * @throws ToolsException
     * @throws \Doctrine\DBAL\Exception
     */
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
        $this->generateurNumeroEmprunt = new GenerateurNumeroEmprunt();
        $this->generateurNumeroAdherent = new GenerateurNumeroAdherent();


        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
        $faker = Factory::create();
        $medias = [];
        $adherents = [];
        for($i = 0; $i < 10; $i++){
            $this->entityManager->getConnection()->beginTransaction();
            try {
                $magazine = new Magazine();
                $magazine->setTitre($faker->sentence(3));
                $magazine->setNumeroMagazine($faker->randomNumber(4, true));
                $magazine->setDateParution(($faker->dateTime())->format('d/m/Y'));
                $magazine->setDateCreation((new DateTime())->format('d/m/Y'));
                $magazine->setDureeEmprunt($magazine->getDureeEmprunt());
                $magazine->setStatut(StatutMedia::STATUT_DISPONIBLE);
                $medias[] = $magazine;
                $this->entityManager->persist($magazine);

                $livre = new Livre();
                $livre->setTitre($faker->sentence(3));
                $livre->setAuteur($faker->name());
                $livre->setIsbn($faker->isbn13());
                $livre->setNbPages($faker->randomNumber(3));
                $livre->setDateCreation((new DateTime())->format('d/m/Y'));
                $livre->setDureeEmprunt($livre->getDureeEmprunt());
                $livre->setStatut(StatutMedia::STATUT_DISPONIBLE);
                $medias[] = $livre;
                $this->entityManager->persist($livre);

                $adherent = new Adherent();
                $adherent->setNumeroAdherent($this->generateurNumeroAdherent->generer());
                $adherent->setNom($faker->firstName());
                $adherent->setPrenom($faker->lastName());
                $adherent->setEmail($faker->email());
                $adherent->setDateAdhesion(new DateTime());
                $adherents[] = $adherent;
                $this->entityManager->persist($adherent);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }catch (Exception $e){
                $this->entityManager->getConnection()->rollBack();
                throw $e;
            }
        }

        foreach($medias as $media){
            $this->entityManager->getConnection()->beginTransaction();
            try{
                $emprunt = new Emprunt();
                $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->execute($this->entityManager));
                $emprunt->setDateEmprunt(new DateTime());
                $emprunt->setDateRetourEstimee((new DateTime())->modify("+{$media->getDureeEmprunt()} days"));
                $emprunt->setAdherent($adherents[0]);
                $emprunt->setMedia($media);
                $media->setStatut(StatutMedia::STATUT_EMPRUNTE);
                $this->entityManager->persist($emprunt);
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }catch(Exception $e){
                $this->entityManager->getConnection()->rollBack();
                throw $e;
            }
        }
    }

    /**
     * @throws NotSupported
     * @throws Exception
     */
    #[test]
    public function retournerUnEmprunt_ValeursCorrectes_OK(){
        $requete = new RetournerUnEmpruntRequete("EM-000000001");
        $retournerUnEmprunt = new RetournerUnEmprunt($this->entityManager, $this->validator);
        $retournerUnEmprunt->execute($requete);
        $repoMedia = $this->entityManager->getRepository(Media::class);
        $repoEmprunt = $this->entityManager->getRepository(Emprunt::class);
        /** @var Emprunt $emprunt */
        $emprunt = $repoEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt]);
        /** @var Media $media */
        $media = $repoMedia->findOneBy(['id' => $emprunt->getMedia()->getId()]);
        $this->assertEquals(StatutMedia::STATUT_DISPONIBLE, $media->getStatut());
        $this->assertEquals((new DateTime())->format("d/m/Y"), ($emprunt->getDateRetourReel())->format("d/m/Y"));
    }

    #[test]
    public function retournerUnEmprunt_NumeroEmpruntAuMauvaisFormat_Exception(){
        $requete = new RetournerUnEmpruntRequete("EM-TADZ80");
        $retournerUnEmprunt = new RetournerUnEmprunt($this->entityManager, $this->validator);
        $repoEmprunt = $this->entityManager->getRepository(Emprunt::class);
        $this->assertNull($repoEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt]));
        $this->expectExceptionMessage("Le numéro d'emprunt doit être au format EM-123456789");
        $retournerUnEmprunt->execute($requete);
    }

    #[test]
    public function retournerUnEmprunt_EmpruntInexistant_Exception(){
        $requete = new RetournerUnEmpruntRequete("EM-000000090");
        $retournerUnEmprunt = new RetournerUnEmprunt($this->entityManager, $this->validator);
        $repoEmprunt = $this->entityManager->getRepository(Emprunt::class);
        $this->assertNull($repoEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt]));
        $this->expectExceptionMessage("L'emprunt n'existe pas");
        $retournerUnEmprunt->execute($requete);
    }

    #[test]
    public function retournerUnEmprunt_EmpruntDejaRestitue_Exception(){
        $requete = new RetournerUnEmpruntRequete("EM-000000001");
        $retournerUnEmprunt = new RetournerUnEmprunt($this->entityManager, $this->validator);
        $repoEmprunt = $this->entityManager->getRepository(Emprunt::class);
        /** @var Emprunt $emprunt */
        $emprunt = $repoEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt]);
        $emprunt->setDateRetourReel(new DateTime());
        $this->expectExceptionMessage("L'emprunt a déjà été retourné.");
        $retournerUnEmprunt->execute($requete);
    }
}