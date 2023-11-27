<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap(["livre" => "Livre", "magazine"=>"Magazine"])]
abstract class Media
{
    #[ORM\Id]
    #[ORM\Column(type : "integer")]
    #[ORM\GeneratedValue]
    protected int $id;
    #[ORM\Column(type : "string", length: 100)]
    protected string $titre;
    #[ORM\Column(type : "integer")]
    protected int $dureeEmprunt;
    #[ORM\Column(type : "string", length: 20)]
    protected string $statut;
    #[ORM\Column(type: 'string', length: 10)]
    protected string $dateCreation;

    public function __construct()
    {
    }

    /**
     * @param string $statut
     */
    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    /**
     * @param int $dureeEmprunt
     */
    public function setDureeEmprunt(int $dureeEmprunt): void
    {
        $this->dureeEmprunt = $dureeEmprunt;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @return int
     */
    public function getDureeEmprunt(): int
    {
        return $this->dureeEmprunt;
    }


    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @param string $dateCreation
     */
    public function setDateCreation(string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

}