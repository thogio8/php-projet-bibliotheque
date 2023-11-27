<?php


namespace App\UserStories\CreerMagazine;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class CreerMagazineRequete
{
    #[Assert\NotBlank(
        message: "Le titre est obligatoire"
    )]
    public string $titre;
    #[Assert\NotBlank(
        message: "Le numéro de magazine est obligatoire"
    )]
    public string $numeroMagazine;
    #[Assert\NotBlank(
        message: "La date de création est obligatoire"
    )]
    public string $dateCreation;

    /**
     * @param string $titre
     * @param string $numeroMagazine
     * @param string $dateCreation
     */
    public function __construct(string $titre, string $numeroMagazine, string $dateCreation)
    {
        $this->titre = $titre;
        $this->numeroMagazine = $numeroMagazine;
        $this->dateCreation = $dateCreation;
    }


}
