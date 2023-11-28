<?php

use App\UserStories\ListeNouveauxMedias\ListeNouveauxMedias;

require "bootstrap.php";


$liste = new ListeNouveauxMedias($entityManager);

dump($liste->execute());