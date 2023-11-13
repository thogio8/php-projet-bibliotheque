<?php

namespace App\UserStories\CreerAdherent;

use App\Entites\Adherent;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\GenerateurNumeroAdherent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\throwException;

class CreerAdherent{
    private EntityManagerInterface $entityManager;
    private GenerateurNumeroAdherent $generateurNumeroAdherent;
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GenerateurNumeroAdherent $generateurNumeroAdherent
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, GenerateurNumeroAdherent $generateurNumeroAdherent, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validator = $validator;
    }

    public function execute(CreerAdherentRequete $requete) : bool{
        // Valider les données en entrées (de la requête)
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach($erreurs as $erreur){
            $e .= $erreur->getMessage();
        }
        if($e != "") {
            throw new \Exception($e);
        }
        // Vérifier que l'email n'existe pas déjà
        $repository = $this->entityManager->getRepository(Adherent::class);
        if($repository->findOneBy(["email" => $requete->email])){
            throw new \Exception("L'email est déjà lié à un autre adhérent.");
        }

        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherent = $this->generateurNumeroAdherent->generer();

        // Vérifier que le numéro n'existe pas déjà
        while($numeroAdherent == $repository->findOneBy(["numeroAdherent" => $numeroAdherent])){
            $numeroAdherent = $this->generateurNumeroAdherent->generer();
        }

        // Créer l'adhérent
        $adherent = new Adherent();
        $adherent->setNumeroAdherent($numeroAdherent);
        $adherent->setNom($requete->nom);
        $adherent->setPrenom($requete->prenom);
        $adherent->setEmail($requete->email);
        $adherent->setDateAdhesion(new \DateTime());
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();
        return true;
    }
}