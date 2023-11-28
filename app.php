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

    $titre = $io->ask("Quel est le titre du livre");
    $isbn = $io->ask("Quel est l'ISBN du livre ?");
    $auteur = $io->ask("Qui est l'auteur du livre ?");
    $dateCreation = $io->ask("Quand à été crée le livre ?");
    $nbPages = $io->ask("Combien le livre fait-il de pages ?");

    $requete = new CreerLivreRequete($titre, $isbn, $auteur, $dateCreation, $nbPages);
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
$app->command('test', function (SymfonyStyle $io){
    $io->table(
        ['Header 1', 'Header 2'],
        [
            ['Cell 1-1', 'Cell 1-2'],
            ['Cell 2-1', 'Cell 2-2'],
            ['Cell 3-1', 'Cell 3-2'],
        ]
    );
});


try {
    $app->run();
} catch (Exception $e) {
    echo $e->getMessage();
}