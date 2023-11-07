<?php

use App\Entites\Adherent;
require_once "bootstrap.php";
require_once "./tests/utils/couleurs.php";

echo PHP_EOL;
echo GREEN_BACKGROUND . BLACK;
echo "Test : Creer un Adherent";
echo RESET;
echo PHP_EOL;

$adherent = new Adherent();
$adherent->setNumeroAdherent("AD-562168");
$adherent->setNom("Gioana");
$adherent->setPrenom("Thomas");
$adherent->setEmail("thomas.gioana@test.fr");
$adherent->setDateAdhesion(new DateTime());
$entityManager->persist($adherent);
$entityManager->flush();