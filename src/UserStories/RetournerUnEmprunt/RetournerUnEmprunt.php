<?php

namespace App\UserStories\RetournerUnEmprunt;

use App\Entites\Emprunt;
use App\Entites\Media;
use App\Entites\StatutMedia;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RetournerUnEmprunt
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

    public function execute(RetournerUnEmpruntRequete $requete){
        //Valider les données en entrée
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach ($erreurs as $erreur) {
            $e .= $erreur->getMessage() . ".\n";
        }
        if ($e != "") {
            throw new Exception($e);
        }

        $repositoryEmprunt = $this->entityManager->getRepository(Emprunt::class);
        $repositoryMedia = $this->entityManager->getRepository(Media::class);
        if (!$repositoryEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt])) {
            throw new Exception("L'emprunt n'existe pas");
        }

        if(($repositoryEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt])->getDateRetourReel()) !== null){
            throw new Exception("L'emprunt a déjà été retourné.");
        }

        $this->entityManager->getConnection()->beginTransaction();
        try {
            $emprunt = $repositoryEmprunt->findOneBy(["numeroEmprunt" => $requete->numeroEmprunt]);
            $emprunt->setDateRetourReel(new DateTime());
            $media = $repositoryMedia->findOneBy(["id" => $emprunt->getMedia()->getId()]);
            $media->setStatut(StatutMedia::STATUT_DISPONIBLE);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        }catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
    }
}