<?php

use App\UserStories\ListeNouveauxMedias\ListeNouveauxMedias;
use Symfony\Component\Validator\Validator\ValidatorInterface;

require "bootstrap.php";

$validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();

$liste = new ListeNouveauxMedias($entityManager, $validator);

dump($liste->execute());

$mediaDispo = new \App\UserStories\RendreDisponibleMedia\RendreDisponibleMedia($entityManager, $validator);
$requete = new \App\UserStories\RendreDisponibleMedia\RendreDisponibleMediaRequete(8);

$mediaDispo->execute($requete);