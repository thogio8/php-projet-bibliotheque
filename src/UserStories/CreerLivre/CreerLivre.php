<?php

namespace App\UserStories\CreerLivre;

use App\Entites\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Services\Constantes;

class CreerLivre
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Constantes $constantes;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->constantes = new Constantes();
    }

    /**
     * @throws Exception
     */
    public function execute(CreerLivreRequete $requete) : bool{
        // Valider les données en entrées
        $erreurs = $this->validator->validate($requete);
        $e = "";
        foreach($erreurs as $erreur){
            $e .= $erreur->getMessage().".\n";
        }
        if($e != ""){
            throw new Exception($e);
        }

        // Vérifier que l'ISBN n'existe pas déjà
        $repository = $this->entityManager->getRepository(Livre::class);
        if($repository->findOneBy(["isbn" => $requete->isbn])){
            throw new Exception("L'ISBN est déjà lié à un autre livre");
        }

        // Créer le livre
        $livre = new Livre();
        $livre->setTitre($requete->titre);
        $livre->setAuteur($requete->auteur);
        $livre->setIsbn($requete->isbn);
        $livre->setNbPages($requete->nbPages);
        $livre->setDateCreation($requete->dateCreation);
        $livre->setDureeEmprunt($livre->getDureeEmprunt());
        $livre->setStatut($this->constantes->_STATUT_NOUVEAU);
        $this->entityManager->persist($livre);
        $this->entityManager->flush();
        return true;
    }
}
