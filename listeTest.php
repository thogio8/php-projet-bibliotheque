<?php


use App\UserStories\ListeNouveauxMedias\ListeNouveauxMedias;
use Symfony\Component\Validator\Validator\ValidatorInterface;

require "bootstrap.php";

$validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();


$empruntRequete = new \App\UserStories\EmprunterUnMedia\EmprunterUnMediaRequete(8,'AD-933300');
$generateurNumeroEmprunt = new \App\Services\GenerateurNumeroEmprunt();
$emprunt = new \App\UserStories\EmprunterUnMedia\EmprunterUnMedia($entityManager, $validator, $generateurNumeroEmprunt);

$emprunt->execute($empruntRequete);