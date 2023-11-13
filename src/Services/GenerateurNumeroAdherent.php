<?php

namespace App\Services;

class GenerateurNumeroAdherent{

    public function generer(): string{
        $numeroAleatoire = rand(0, 999999);
        $numeroAleatoire = str_pad(strval($numeroAleatoire), 6, '0', STR_PAD_LEFT);
        return "AD-".$numeroAleatoire;
    }
}