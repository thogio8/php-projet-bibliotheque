<?php

namespace App\UserStories\RendreDisponibleMedia;

use App\Entites\Magazine;
use App\Entites\Media;
use App\Entites\StatutMedia;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RendreDisponibleMedia{
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
     * @param RendreDisponibleMediaRequete $requete
     * @return bool
     * @throws Exception
     */
    public function execute(RendreDisponibleMediaRequete $requete): bool{
        //Valider les données en entrée
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach ($erreurs as $erreur) {
            $e .= $erreur->getMessage() . ".\n";
        }
        if ($e != "") {
            throw new Exception($e);
        }

        //Vérifier que le média existe
        $repository = $this->entityManager->getRepository(Media::class);
        if (!$repository->findOneBy(["id" => $requete->id])) {
            throw new Exception("Le média n'existe pas");
        }

        //Vérfier que le média a le statut nouveau
        if($repository->findOneBy(["statut" => $requete->id]) !== StatutMedia::STATUT_NOUVEAU){
            throw new Exception("Le média n'a pas le statut Nouveau.");
        }

        $media = $repository->findOneBy(["id" => $requete->id]);
        $media->setStatut(StatutMedia::STATUT_DISPONIBLE);
        $this->entityManager->persist($media);
        $this->entityManager->flush();
        return true;
    }
}