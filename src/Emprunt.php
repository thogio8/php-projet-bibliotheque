<?php

namespace App;

class Emprunt
{
    private int $id;
    private int $numeroEmprunt;
    private \DateTime $dateEmprunt;
    private \DateTime $dateRetourEstimee;
    private \DateTime $dateRetourReel;
    private Adherent $adherent;
    private Media $media;

    public function __construct()
    {

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
     * @return int
     */
    public function getNumeroEmprunt(): int
    {
        return $this->numeroEmprunt;
    }

    /**
     * @param int $numeroEmprunt
     */
    public function setNumeroEmprunt(int $numeroEmprunt): void
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }

    /**
     * @return \DateTime
     */
    public function getDateEmprunt(): \DateTime
    {
        return $this->dateEmprunt;
    }

    /**
     * @param \DateTime $dateEmprunt
     */
    public function setDateEmprunt(\DateTime $dateEmprunt): void
    {
        $this->dateEmprunt = $dateEmprunt;
    }

    /**
     * @return \DateTime
     */
    public function getDateRetourEstimee(): \DateTime
    {
        return $this->dateRetourEstimee;
    }

    /**
     * @param \DateTime $dateRetourEstimee
     */
    public function setDateRetourEstimee(\DateTime $dateRetourEstimee): void
    {
        $this->dateRetourEstimee = $dateRetourEstimee;
    }

    /**
     * @return \DateTime
     */
    public function getDateRetourReel(): \DateTime
    {
        return $this->dateRetourReel;
    }

    /**
     * @param \DateTime $dateRetourReel
     */
    public function setDateRetourReel(\DateTime $dateRetourReel): void
    {
        $this->dateRetourReel = $dateRetourReel;
    }

    /**
     * @return Adherent
     */
    public function getAdherent(): Adherent
    {
        return $this->adherent;
    }

    /**
     * @param Adherent $adherent
     */
    public function setAdherent(Adherent $adherent): void
    {
        $this->adherent = $adherent;
    }

    /**
     * @return Media
     */
    public function getMedia(): Media
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media): void
    {
        $this->media = $media;
    }


}