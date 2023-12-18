<?php

namespace App\UserStories\ListeNouveauxMedias;

use App\Entites\Media;
use Doctrine\ORM\EntityManagerInterface;


class ListeNouveauxMedias
{
    private EntityManagerInterface $entityManager;


    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): array
    {
        $repository = $this->entityManager->getRepository(Media::class);
        $mediaRepo = $repository->findBy(["statut" => "Nouveau"]);
        $medias = [];
        foreach($mediaRepo as $media){
            $medias[] = ["id" => $media->getId(), "titre" => $media->getTitre(), "statut" => $media->getStatut(), "dateCreation" => $media->getDateCreation(), "type" => $media->getType()];
        }
        usort($medias, function ($a, $b) {
            return strtotime(str_replace('/', '-', $b['dateCreation'])) - strtotime(str_replace('/', '-',$a['dateCreation']));
        });
        return $medias;
    }
}