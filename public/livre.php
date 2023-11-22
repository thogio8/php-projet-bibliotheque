<?php

use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use Symfony\Component\Validator\ValidatorBuilder;

require "../bootstrap.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $requete = new CreerLivreRequete($_POST["title"], $_POST["isbn"], $_POST["auteur"], $_POST["datecrea"], $_POST["nbpages"] );
    $validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
    $creerLivre = new CreerLivre($entityManager, $validator);
    try{
        $creerLivre->execute($requete);
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
    <title>Ajouter un livre</title>
</head>
<body>
<h1 id="titre">Ajouter un livre</h1>
<form method="post" class="form">
    <div class="form-field">
        <label for="title">Saisissez le titre :</label>
        <input type="text" name="title" id="title">
    </div>

    <div class="form-field">
        <label for="isbn">Saisissez l'ISBN :</label>
        <input type="text" name="isbn" id="isbn">
    </div>

    <div class="form-field">
        <label for="auteur">Saisissez l'auteur :</label>
        <input type="text" name="auteur" id="auteur">
    </div>

    <div class="form-field">
        <label for="datecrea">Saisissez la date de création :</label>
        <input type="text" name="datecrea" id="datecrea">
    </div>

    <div class="form-field">
        <label for="nbpages">Saisissez le nombre de pages :</label>
        <input type="number" name="nbpages" id="nbpages" value="0">
    </div>

    <div class="submit">
        <input type="submit" value="Ajouter le livre">
    </div>
</form>
<?php
if(isset($errors)){ ?>
    <p>Erreurs : <?= $errors ?></p>
<?php }?>

</body>
</html>