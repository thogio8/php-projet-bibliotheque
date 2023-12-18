<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;


#[ORM\Entity]
class Magazine extends Media
{
    #[ORM\Column(type: "integer")]
    private int $numeroMagazine;
    #[ORM\Column(type: "string", length: 10)]
    private string $dateParution;

    public function __construct()
    {
        parent::__construct();
        $this->dureeEmprunt = 10;
    }

    /**
     * @return int
     */
    public function getNumeroMagazine(): int
    {
        return $this->numeroMagazine;
    }

    /**
     * @param int $numeroMagazine
     */
    public function setNumeroMagazine(int $numeroMagazine): void
    {
        $this->numeroMagazine = $numeroMagazine;
    }

    /**
     * @return string
     */
    public function getDateParution(): string
    {
        return $this->dateParution;
    }

    /**
     * @param string $dateParution
     */
    public function setDateParution(string $dateParution): void
    {
        $this->dateParution = $dateParution;
    }
    public function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}