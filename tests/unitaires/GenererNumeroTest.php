<?php

namespace Tests\unitaires;

use App\Services\GenerateurNumeroAdherent;
use PHPUnit\Framework\TestCase;

class GenererNumeroTest extends TestCase{

    /**
     * @test
     */
    public function genererNumero_NumeroGenereContient9Caracteres_Vrai(){
       $generateurNumeroAdherent = new GenerateurNumeroAdherent();
       $numeroAdherent = $generateurNumeroAdherent->generer();
       $this->assertEquals(9, strlen($numeroAdherent));
    }

    /**
     * @test
     */
    public function genererNumero_NumeroGenereCommencantParAD_Vrai(){
        $generateurNumeroAdherent = new GenerateurNumeroAdherent();
        $numeroAdherent = $generateurNumeroAdherent->generer();
        $this->assertEquals("AD-", substr($numeroAdherent, 0, 3));
    }

    /**
     * @test
     */
    public function genererNumero_PartieNumeriqueNumeroGenereComposeUniquementDeChiffres_Vrai(){
        $generateurNumeroAdherent = new GenerateurNumeroAdherent();
        $numeroAdherent = $generateurNumeroAdherent->generer();
        $this->assertEquals(6, strspn(substr($numeroAdherent, 3),"0123456789"));
    }
}