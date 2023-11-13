<?php

namespace App\Entites;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity]
class Adherent
{
    #[Id]
    #[Column(type : "integer")]
    #[GeneratedValue]
    private int $id;
    #[Column(type: "string", length: 9)]
    private string $numeroAdherent;
    #[Column(type: "string", length: 80)]

    private string $prenom;
    #[Column(type: "string", length: 80)]
    private string $nom;
    #[Column(type: "string", length: 150)]
    private string $email;
    #[Column(type: "datetime")]
    private \DateTime $dateAdhesion;

    public function __construct (){

    }

    private function genererNumero() : string {
        $numeroAleatoire = rand(0, 999999);
        $numeroAleatoire = str_pad(strval($numeroAleatoire), 6, '0', STR_PAD_LEFT);
        return "AD-".$numeroAleatoire;
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
    public function getNumeroAdherent(): string
    {
        return $this->numeroAdherent;
    }

    /**
     * @param string $numeroAdherent
     */
    public function setNumeroAdherent(string $numeroAdherent): void
    {
        $this->numeroAdherent = $numeroAdherent;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getDateAdhesion(): \DateTime
    {
        return $this->dateAdhesion;
    }

    /**
     * @param \DateTime $dateAdhesion
     */
    public function setDateAdhesion(\DateTime $dateAdhesion): void
    {
        $this->dateAdhesion = $dateAdhesion;
    }


}