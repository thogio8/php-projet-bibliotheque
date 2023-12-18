<?php

namespace App\UserStories\EmprunterUnMedia;
use Symfony\Component\Validator\Constraints as Assert;

class EmprunterUnMediaRequete
{
    #[Assert\NotBlank(
        message: "Veuillez renseignez le numéro du média à emprunter"
    )]
    #[Assert\GreaterThan(0, message: "L'id du media est strictement positif")]
    public ?int $id;
    #[Assert\NotBlank(
        message: "Veuillez renseignez le numéro de l'adhérent qui souhaite emprunter un média"
    )]
    public ?string $numeroAdherent;

    /**
     * @param int|null $id
     * @param string|null $numeroAdherent
     */
    public function __construct(?int $idMedia, ?string $numeroAdherent)
    {
        $this->id = $idMedia;
        $this->numeroAdherent = $numeroAdherent;
    }


}