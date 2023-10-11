<?php

namespace App;

class Adherent
{
    private int $id;
    private string $numeroAdherent;
    private string $prenom;
    private string $nom;
    private string $email;
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
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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