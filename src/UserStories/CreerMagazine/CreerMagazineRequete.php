<?php


namespace App\UserStories\CreerMagazine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class CreerMagazineRequete
{
    #[Assert\NotBlank(
        message: "Le titre est obligatoire"
    )]
    public ?string $titre;
    #[Assert\NotBlank(
        message: "Le numéro de magazine est obligatoire"
    )]
    public ?string $numeroMagazine;
    #[Assert\NotBlank(
        message: "La date de parution est obligatoire"
    )]
    public ?string $dateParution;

    /**
     * @param ?string $titre
     * @param ?string $numeroMagazine
     * @param ?string $dateParution
     */
    public function __construct(?string $titre, ?string $numeroMagazine, ?string $dateParution)
    {
        $this->titre = $titre;
        $this->numeroMagazine = $numeroMagazine;
        $this->dateParution = $dateParution;
    }


}
