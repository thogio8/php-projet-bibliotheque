<?php

require 'vendor/autoload.php';
/** @var EntityManager $entityManager */
require 'bootstrap.php';

// Définir les commandes
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use Doctrine\ORM\EntityManager;
use Silly\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ValidatorBuilder;

$app = new Application();

$app->command("bibliotheque:livre:create", function (SymfonyStyle $io) use ($entityManager){
    $io->title("Création d'un livre");
    $io->note("Toutes les informations doivent être saisies");
    do {
        $titre = $io->ask("Quel est le titre du livre");
        if($titre === null){
            $io->error("Veuillez saisir le titre du livre");
        }
        if(gettype($titre) != "string"){
            $io->error("Le titre doit être saisi sous forme d'une chaine de caractères.");
        }
    }while($titre === null || gettype($titre) != "string");

    do {
        $isbn = $io->ask("Quel est l'ISBN du livre ?");
        if($isbn === null){
            $io->error("Veuillez saisir l'ISBN du livre");
        }
        if(gettype($isbn) != "string"){
            $io->error("L'ISBN doit être saisi sous forme d'une chaine de caractères.");
        }
    }while($isbn === null || gettype($isbn) != "string");

    do {
        $auteur = $io->ask("Qui est l'auteur du livre ?");
        if($auteur === null){
            $io->error("Veuillez saisir l'auteur du livre");
        }
        if(gettype($auteur) != "string"){
            $io->error("L'auteur doit être saisi sous forme d'une chaine de caractères.");
        }
    }while($auteur === null || gettype($auteur) != "string");

    do {
        $dateCreation = $io->ask("Quand à été crée le livre ?");
        if($dateCreation === null){
            $io->error("Veuillez saisir la date de création du livre");
        }
        if(gettype($dateCreation) != "string"){
            $io->error("La date de création doit être saisie sous forme d'une chaine de caractères.");
        }
    }while($dateCreation === null || gettype($dateCreation) != "string");

    do {
        $nbPages = $io->ask("Combien le livre fait-il de pages ?");
        if($nbPages === null){
            $io->error("Veuillez saisir le nombre de pages du livre");
        }
        if(gettype($nbPages) != "integer"){
            $io->error("Le nombre de pages doit être saisi sous forme d'un nombre entier.");
        }
    }while($nbPages === null || gettype($nbPages) != "integer");

    $requete = new CreerLivreRequete($titre, $isbn, $auteur, $dateCreation, $nbPages);
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerLivre = new CreerLivre($entityManager, $validator);
    try{
        $creerLivre->execute($requete);
    }catch (Exception $e){
        $io->error($e->getMessage());
        die();
    }
    $io->success('Le livre a bien été crée');
});

$app->command("bibliotheque:magazine:create", function (SymfonyStyle $io) use ($entityManager){
    $io->title("Création d'un magazine");
    $io->note("Toutes les informations doivent être saisies");
    do {
        $titre = $io->ask("Quel est le titre du magazine");
        if($titre === null){
            $io->error("Veuillez saisir le titre du magazine");
        }
        if(gettype($titre) != "string"){
            $io->error("Le titre doit être saisi sous forme d'une chaine de caractères.");
        }
    }while($titre === null || gettype($titre) != "string");

    do {
        $numMagazine = $io->ask("Quel est le numéro du magazine ?");
        if($numMagazine === null){
            $io->error("Veuillez saisir le numéro du magazine");
        }
        if(gettype($numMagazine) != "integer"){
            $io->error("Le numéro du magazine doit être saisi sous forme d'un nombre entier.");
        }
    }while($numMagazine === null || gettype($numMagazine) != "integer");

    do {
        $dateCreation = $io->ask("Quand à été crée le magazine ?");
        if($dateCreation === null){
            $io->error("Veuillez saisir la date de création du magazine");
        }
        if(gettype($dateCreation) != "string"){
            $io->error("La date de création doit être saisi sous forme d'une chaine de caractères.");
        }
    }while($dateCreation === null || gettype($dateCreation) != "string");

    $requete = new CreerMagazineRequete($titre, $numMagazine, $dateCreation);
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerMagazine = new CreerMagazine($entityManager, $validator);
    try{
        $creerMagazine->execute($requete);
    }catch (Exception $e){
        $io->error($e->getMessage());
        die();
    }
    $io->success('Le magazine a bien été crée');
});

try {
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
}