<?php

namespace App\UserStories\EmprunterUnMedia;

use App\Entites\Adherent;
use App\Entites\Emprunt;
use App\Entites\Media;
use App\Entites\StatutMedia;
use App\Services\GenerateurNumeroEmprunt;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmprunterUnMedia
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private GenerateurNumeroEmprunt $generateurNumeroEmprunt;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param GenerateurNumeroEmprunt $generateurNumeroEmprunt
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, GenerateurNumeroEmprunt $generateurNumeroEmprunt)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->generateurNumeroEmprunt = $generateurNumeroEmprunt;
    }



    public function execute(EmprunterUnMediaRequete $requete) : bool{
        //Valider les données en entrée
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach ($erreurs as $erreur) {
            $e .= $erreur->getMessage() . ".\n";
        }
        if ($e != "") {
            throw new Exception($e);
        }

        $repositoryMedia = $this->entityManager->getRepository(Media::class);
        $media = $repositoryMedia->findOneBy(["id" => $requete->id]);

        //Vérifier que le média existe
        if ($media === null) {
            throw new Exception("Le média n'existe pas");
        }


        //Vérifier que le média est disponible
        if($media->getStatut() !== StatutMedia::STATUT_DISPONIBLE){
            throw new Exception("Le média n'est pas disponible à l'emprunt");
        }

        //Vérifier que l'adhérent existe
        $repositoryAdherent = $this->entityManager->getRepository(Adherent::class);
        $adherent = $repositoryAdherent->findOneBy(["numeroAdherent" => $requete->numeroAdherent]);

        if ($adherent === null) {
            throw new Exception("L'adhérent n'existe pas");
        }

        if($adherent->getDateAdhesion()->modify("+365 days") < new \DateTime()){
            throw new Exception("L'adhésion n'est plus valide");
        }

        $this->entityManager->getConnection()->beginTransaction();
        try{
            $emprunt = new Emprunt();
            $emprunt->setNumeroEmprunt($this->generateurNumeroEmprunt->execute($this->entityManager));
            $emprunt->setDateEmprunt(new \DateTime());
            $emprunt->setDateRetourEstimee((new \DateTime())->modify("+{$media->getDureeEmprunt()} days"));
            $emprunt->setAdherent($adherent);
            $emprunt->setMedia($media);
            $media->setStatut(StatutMedia::STATUT_EMPRUNTE);
            $this->entityManager->persist($emprunt);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        }catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }

        return true;
    }
}