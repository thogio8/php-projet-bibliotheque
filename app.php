<?php

require 'vendor/autoload.php';
/** @var EntityManager $entityManager */
require 'bootstrap.php';

// Définir les commandes
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use App\UserStories\ListeNouveauxMedias\ListeNouveauxMedias;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMedia;
use App\UserStories\RendreDisponibleMedia\RendreDisponibleMediaRequete;
use Doctrine\ORM\EntityManager;
use Silly\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ValidatorBuilder;

$app = new Application();

$app->command("bibliotheque:livre:create", function (SymfonyStyle $io) use ($entityManager){
    $io->title("Création d'un livre");
    $io->note("Toutes les informations doivent être saisies");

    $titre = $io->ask("Quel est le titre du livre");
    $isbn = $io->ask("Quel est l'ISBN du livre ?");
    $auteur = $io->ask("Qui est l'auteur du livre ?");
    $nbPages = $io->ask("Combien le livre fait-il de pages ?");

    $requete = new CreerLivreRequete($titre, $isbn, $auteur, $nbPages);
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerLivre = new CreerLivre($entityManager, $validator);
    try{
        $creerLivre->execute($requete);
    }catch (Exception $e){
        $erreurs = $e->getMessage();
    }
    if(isset($erreurs)){
        $io->error($erreurs);
        die();
    }
    $io->success('Le livre a bien été crée');
});

$app->command("bibliotheque:magazine:create", function (SymfonyStyle $io) use ($entityManager){
    $io->title("Création d'un magazine");
    $io->note("Toutes les informations doivent être saisies");

    $titre = $io->ask("Quel est le titre du magazine");
    $numMagazine = $io->ask("Quel est le numéro du magazine ?");
    $dateParution = $io->ask("Quand à été crée le magazine ?");


    $requete = new CreerMagazineRequete($titre, $numMagazine, $dateParution);
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerMagazine = new CreerMagazine($entityManager, $validator);
    try{
        $creerMagazine->execute($requete);
    }catch (Exception $e){
        $erreurs = $e->getMessage();
    }
    if(isset($erreurs)){
        $io->error($erreurs);
        die();
    }
    $io->success('Le magazine a bien été crée');
});

$app->command('bibliotheque:media:select:new', function(SymfonyStyle $io) use ($entityManager){
    $medias = (new ListeNouveauxMedias($entityManager))->execute();
    $table = $io->createTable();
    $table->setHeaderTitle("Liste des nouveaux médias");
    $table->setHeaders(['id', 'titre', 'statut', 'dateCreation', 'type']);
    $table->setRows($medias);
    $table->render();
});

$app->command('bibliotheque:media:update:disponible', function(SymfonyStyle $io) use ($entityManager){
    $io->title("Rendre un média disponible");
    $io->note("Toutes les informations doivent être saisies");
    $id = $io->ask("Quel est l'id du média à modifier ?");
    $requete = new RendreDisponibleMediaRequete($id);
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $rendreDisponible = new RendreDisponibleMedia($entityManager,$validator);
    try{
        $rendreDisponible->execute($requete);
    }catch (Exception $e){
        $erreurs = $e->getMessage();
    }
    if(isset($erreurs)){
        $io->error($erreurs);
        die();
    }
    $io->success('Le statut du média a bien été modifié');
});


try {
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
}