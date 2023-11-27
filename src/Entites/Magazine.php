<?php

namespace App\Entites;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Collection;


#[ORM\Entity]
class Magazine extends Media
{
    #[ORM\Column(type: "integer")]
    private int $numeroMagazine;

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
     * @return \DateTime
     */
    public function getDatePublication(): \DateTime
    {
        return $this->datePublication;
    }

    /**
     * @param \DateTime $datePublication
     */
    public function setDatePublication(\DateTime $datePublication): void
    {
        $this->datePublication = $datePublication;
    }


}