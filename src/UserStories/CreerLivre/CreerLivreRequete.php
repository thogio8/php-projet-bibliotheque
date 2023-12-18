<?php

namespace App\UserStories\CreerLivre;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete{
    #[Assert\NotBlank(
        message: "Le titre est obligatoire."
    )]
    public ?string $titre;
    #[Assert\NotBlank(
        message: "L'ISBN est obligatoire."
    )]
    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_13,
        message: "L'ISBN n'est pas valide.",
    )]
    public ?string $isbn;
    #[Assert\NotBlank(
        message: "L'auteur est obligatoire."
    )]
    public ?string $auteur;

    #[Assert\GreaterThan(0,
        message: "Le nombre de pages ne peut pas être inférieur ou égal 0."
    )]
    public ?int $nbPages;

    /**
     * @param ?string $titre
     * @param ?string $isbn
     * @param ?string $auteur
     * @param ?int $nbPages
     */
    public function __construct(?string $titre, ?string $isbn, ?string $auteur, ?int $nbPages)
    {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->nbPages = $nbPages;
    }


}