<?php

namespace App\Entites;

class Magazine extends Media
{
    private int $numeroMagazine;
    private \DateTime $datePublication;

    public function __construct()
    {
        parent::__construct();
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