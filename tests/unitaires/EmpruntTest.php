<?php

namespace App\Tests\unitaires;

require "vendor/autoload.php";

use App\Entites\Emprunt;
use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase{
    /**
     * @test
     */
    public function estEnCours_SiDateNonPrecisee_EgalAVrai(){
        $emprunt = new Emprunt();
        $this->assertTrue($emprunt->enCours());
    }

    /**
     * @test
     */
    public function estPasEnCours_SiDatePrecisee_EgalAFaux(){
        $emprunt = new Emprunt();
        $emprunt->setDateRetourReel(new \DateTime());
        $this->assertFalse($emprunt->enCours());
    }

    /**
     * @test
     */
    public function estEnRetard_SiEncoreEnCoursEtDateEstimeeSuperieurADateDuJour_EgalAVrai(){
        $emprunt = new Emprunt();
        $emprunt->setDateRetourEstimee(\DateTime::createFromFormat("d/m/Y", "10/10/2023"));
        $this->assertTrue($emprunt->estEnRetard());
    }

    /**
     * @test
     */
    public function estPasEnRetard_SiPlusEnCoursEtDateEstimeeInferieurADateDuJour_EgalAFaux(){
        $emprunt = new Emprunt();
        $emprunt->setDateRetourEstimee(\DateTime::createFromFormat("d/m/Y", "15/10/2023"));
        $this->assertFalse($emprunt->estEnRetard());
    }
}