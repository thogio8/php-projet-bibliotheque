<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Livre extends Media
{
    #[ORM\Column(type : "string", length: 20)]
    private string $isbn;
    #[ORM\Column(type : "string", length: 80)]
    private string $auteur;
    #[ORM\Column(type : "integer")]
    private ?int $nbPages;

    public function __construct()
    {
        parent::__construct();
        $this->dureeEmprunt = 21;
    }

    /**
     * @param int $dureeEmprunt
     */
    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getAuteur(): string
    {
        return $this->auteur;
    }

    /**
     * @param string $auteur
     */
    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }


    /**
     * @return int
     */
    public function getNbPages(): int
    {
        return $this->nbPages;
    }

    /**
     * @param int $nbPages
     */
    public function setNbPages(int $nbPages): void
    {
        $this->nbPages = $nbPages;
    }


}