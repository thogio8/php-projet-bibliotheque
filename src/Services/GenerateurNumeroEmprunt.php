<?php

namespace App\Services;

use App\Entites\Emprunt;
use Doctrine\ORM\EntityManager;


class GenerateurNumeroEmprunt
{
    public function execute(EntityManager $entityManager) : string {
            // Récupérer le dernier emprunt enregistré
            $dernierEmprunt = $entityManager->getRepository(Emprunt::class)->findOneBy(['id' => 'DESC']);

            // Si aucun emprunt n'a encore été enregistré, définir le numéro d'emprunt à "EM-000000001"
            if (!$dernierEmprunt) {
                return 'EM-000000001';
            }

            // Récupérer le numéro d'emprunt du dernier emprunt enregistré
            $dernierNumeroEmprunt = $dernierEmprunt->getNumeroEmprunt();

            // Incrémenter le numéro d'emprunt du dernier emprunt enregistré de 1
            $nouveauNumeroEmprunt = (int) substr($dernierNumeroEmprunt, 3) + 1;

            // Générer le nouveau numéro d'emprunt au format "EM-999999999"
            return 'EM-' . str_pad($nouveauNumeroEmprunt, 9, '0', STR_PAD_LEFT);

    }
}