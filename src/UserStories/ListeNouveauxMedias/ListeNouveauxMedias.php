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
        $mediasRepo = $repository->findBy(["statut" => "Nouveau"]);
        foreach($mediasRepo as $mediaRepo) {
            $medias[] = implode('/',array_reverse(explode("/", $mediaRepo)));
        }
        array_multisort($medias, SORT_DESC);
        dump($medias);
        return $medias;
    }
}