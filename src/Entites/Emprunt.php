<?php

namespace App\Entites;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

require "vendor/autoload.php";

#[ORM\Entity]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[ORM\Column(type: 'string')]
    private string $numeroEmprunt;
    #[ORM\Column(type: 'datetime')]
    private DateTime $dateEmprunt;
    #[ORM\Column(type: 'datetime')]
    private DateTime $dateRetourEstimee;
    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $dateRetourReel;
    #[ORM\ManyToOne(targetEntity: Adherent::class)]
    #[ORM\JoinColumn(name: 'adherent', referencedColumnName: 'id')]
    private Adherent $adherent;
    #[ORM\ManyToOne(targetEntity: Media::class)]
    #[ORM\JoinColumn(name: 'media', referencedColumnName: 'id')]
    private Media $media;

    public function __construct()
    {

    }

    public function enCours(): bool
    {
        if (isset($this->dateRetourReel)) {
            return false;
        }
        return true;
    }

    public function estEnRetard(): bool
    {
        if ($this->enCours() && $this->dateRetourEstimee <= new DateTime()) {
            return true;
        }
        return false;
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
    public function getNumeroEmprunt(): string
    {
        return $this->numeroEmprunt;
    }

    /**
     * @param int $numeroEmprunt
     */
    public function setNumeroEmprunt(string $numeroEmprunt): void
    {
        $this->numeroEmprunt = $numeroEmprunt;
    }

    /**
     * @return DateTime
     */
    public function getDateEmprunt(): DateTime
    {
        return $this->dateEmprunt;
    }

    /**
     * @param DateTime $dateEmprunt
     */
    public function setDateEmprunt(DateTime $dateEmprunt): void
    {
        $this->dateEmprunt = $dateEmprunt;
    }

    /**
     * @return DateTime
     */
    public function getDateRetourEstimee(): DateTime
    {
        return $this->dateRetourEstimee;
    }

    /**
     * @param DateTime $dateRetourEstimee
     */
    public function setDateRetourEstimee(DateTime $dateRetourEstimee): void
    {
        $this->dateRetourEstimee = $dateRetourEstimee;
    }

    /**
     * @return DateTime
     */
    public function getDateRetourReel(): DateTime
    {
        return $this->dateRetourReel;
    }

    /**
     * @param ?DateTime $dateRetourReel
     */
    public function setDateRetourReel(?DateTime $dateRetourReel): void
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