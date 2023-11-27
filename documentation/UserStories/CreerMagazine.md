# Créer un Magazine

## Description :

**En tant que** bibliothécaire  
**Je veux** créer un magazine  
**Afin de** le rendre accessible aux adhérents de la bibliothèque

## Critères d'acceptation :

### Validation des données :
* L'utilisateur doit saisir le titre, le numéro du magazine, la date de création.
* Le numéro de magazine doit être unique.
* Le nombre de pages doit être strictement supérieur à 0.


Les données sont vérifiés à l'aide du Validator de **Symfony** :

```php
    #[Assert\NotBlank(
        message: "Le titre est obligatoire"
    )]
    public string $titre;
    #[Assert\NotBlank(
        message: "Le numéro de magazine est obligatoire"
    )]
    public string $numeroMagazine;
    #[Assert\NotBlank(
        message: "La date de création est obligatoire"
    )]
    public string $dateCreation
```

Pour vérifier que le numéro de magazine n'est pas déjà lié à un autre magazine, on va utiliser l'EntityManager :
```php
// Vérifier que le numéro de magazine n'existe pas déjà
        $repository = $this->entityManager->getRepository(Magazine::class);
        if ($repository->findOneBy(["numeroMagazine" => $requete->numeroMagazine])) {
            throw new Exception("Le numéro de magazine est déjà lié à un autre magazine");
        }
```


### Enregistrement dans la base de données :
Le magazine doit être ajouter dans la base de données une fois que les données sont validés.
```php
// Créer le magazine
        $magazine = new Magazine();
        $magazine->setTitre($requete->titre);
        $magazine->setNumeroMagazine($requete->numeroMagazine);
        $magazine->setDateCreation($requete->dateCreation);
        $magazine->setDureeEmprunt($magazine->getDureeEmprunt());
        //Un média qui vient d'être crée doit avoir le statut NOUVEAU
        $magazine->setStatut($this->constantes->_STATUT_NOUVEAU);
        $this->entityManager->persist($magazine);
        $this->entityManager->flush();
```

## Étapes :

1. Le bibliothécaire va saisir les informations du magazine en exécutant la commande :     
   ```php app.php bibliotheque:magazine:create```
2. Le programme vérifie au fur et à mesure de la saisie la validité des données.
3. Le programme va ensuite insérer les enregistrements dans la base données.

## Tests :

* Le bibliothécaire doit saisir des informations valides : elles doivent être renseignés.
* Le programme va contrôler l'unicité du numéro de magazine en se servant du validator.
* Le magazine est bien ajouté à la base de données.
