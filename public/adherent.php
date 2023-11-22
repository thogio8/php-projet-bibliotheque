<?php

use App\Services\GenerateurNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use Symfony\Component\Validator\ValidatorBuilder;

require "../bootstrap.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $requete = new CreerAdherentRequete($_POST["name"], $_POST["surname"], $_POST["email"]);
    $generateur = new GenerateurNumeroAdherent();
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerAdherent = new CreerAdherent($entityManager, $generateur, $validator);
    try{
        $creerAdherent->execute($requete);
    }catch (Exception $e){
        $errors = $e->getMessage();
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/style.css">
    <title>Ajouter un adhérent</title>
</head>
<body>
<h1 id="titre">Ajouter un adhérent</h1>
<form method="post" class="form">
    <div class="form-field">
        <label for="surname">Saisissez votre nom :</label>
        <input type="text" name="surname" id="surname">
    </div>

    <div class="form-field">
        <label for="name">Saisissez votre prénom :</label>
        <input type="text" name="name" id="name">
    </div>

    <div class="form-field">
        <label for="email">Saisissez votre email :</label>
        <input type="text" name="email" id="email">
    </div>

    <div class="submit">
        <input type="submit" value="Ajouter l'adhérent">
    </div>
</form>
<?php
if(isset($errors)){ ?>
    <p>Erreurs : <?= $errors ?></p>
<?php }?>

</body>
</html>