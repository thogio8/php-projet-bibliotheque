<?php

namespace App\UserStories\CreerLivre;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete{
    #[Assert\NotBlank(
        message: "Le titre est obligatoire"
    )]
    public string $titre;
    #[Assert\NotBlank(
        message: "L'ISBN est obligatoire"
    )]
    public string $isbn;
    #[Assert\NotBlank(
        message: "L'auteur est obligatoire"
    )]
    public string $auteur;

    #[Assert\GreaterThan(0,
        message: "Le nombre de pages ne peut pas être inférieur ou égal 0"
    )]
    public int $nbPages;
    #[Assert\NotBlank(
        message: "La date de création est obligatoire"
    )]
    public string $dateCreation;

    /**
     * @param string $titre
     * @param string $isbn
     * @param string $auteur
     * @param int $nbPages
     * @param string $dateCreation
     */
    public function __construct(string $titre, string $isbn, string $auteur, string $dateCreation, int $nbPages)
    {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->nbPages = $nbPages;
        $this->dateCreation = $dateCreation;
    }


}