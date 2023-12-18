<?php
namespace App\UserStories\RendreDisponibleMedia;

use Symfony\Component\Validator\Constraints as Assert;

class RendreDisponibleMediaRequete{
    #[Assert\NotBlank(
        message: "Veuillez renseignez le numéro du média à rendre disponible"
    )]
    #[Assert\GreaterThan(0, message: "L'id du media est strictement positif.")]
    public ?int $id;

    /**
     * @param int|null $id
     */
    public function __construct(?int $id){
        $this->id = $id;
    }
}