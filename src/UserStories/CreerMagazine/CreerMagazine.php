<?php


namespace App\UserStories\CreerMagazine;

use App\Entites\Magazine;
use App\Entites\StatutMedia;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerMagazine
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function execute(CreerMagazineRequete $requete): bool
    {
        // Valider les données en entrées
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach ($erreurs as $erreur) {
            $e .= $erreur->getMessage() . ".\n";
        }
        if ($e != "") {
            throw new Exception($e);
        }

        // Vérifier que le numéro de magazine n'existe pas déjà
        $repository = $this->entityManager->getRepository(Magazine::class);
        if ($repository->findOneBy(["numeroMagazine" => $requete->numeroMagazine])) {
            throw new Exception("Le numéro de magazine est déjà lié à un autre magazine");
        }

        // Créer le magazine
        $magazine = new Magazine();
        $magazine->setTitre($requete->titre);
        $magazine->setNumeroMagazine($requete->numeroMagazine);
        $magazine->setDateParution($requete->dateParution);
        $magazine->setDateCreation((new \DateTime())->format('d/m/Y'));
        $magazine->setDureeEmprunt($magazine->getDureeEmprunt());
        $magazine->setStatut(StatutMedia::STATUT_NOUVEAU);
        $this->entityManager->persist($magazine);
        $this->entityManager->flush();
        return true;
    }
}
