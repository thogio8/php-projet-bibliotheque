<?php

namespace App\UserStories\RetournerUnEmprunt;

use Symfony\Component\Validator\Constraints as Assert;

class RetournerUnEmpruntRequete
{
    #[Assert\NotBlank(
        message: "Veuillez renseigner le numéro de l'emprunt à retourner"
    )]
    #[Assert\Regex(
        pattern: "/^EM-\d{9}$/",
        message: "Le numéro d'emprunt doit être au format EM-123456789",
        match: true
    )]
    public ?string $numeroEmprunt;

    /**
     * @param string|null $numeroEmprunt
     */
    public function __construct(?string $numeroEmprunt)
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }


}