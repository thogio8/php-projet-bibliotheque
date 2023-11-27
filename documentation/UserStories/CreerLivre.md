# Créer un livre

## Description :

**En tant que** bibliothécaire  
**Je veux** créer un livre  
**Afin de** le rendre accessible aux adhérents de la bibliothèque

## Critères d'acceptation :

### Validation des données :
* L'utilisateur doit saisir l'ISBN du livre, le titre, l'auteur, la date de création ainsi que le nombre de pages.
* L'ISBN doit être unique.
* Le nombre de pages doit être strictement supérieur à 0.


Les données sont vérifiés à l'aide du Validator de **Symfony** :

```php
    #[Assert\NotBlank(
        message: "Le titre est obligatoire"
    )]
    public string $titre;
    #[Assert\NotBlank(
        message: "L'ISBN est obligatoire"
    )]
    public string $isbn;
    #[Assert\NotBlank(
        message: "L'auteur est obligatoire"
    )]
    public string $auteur;

    #[Assert\GreaterThan(0,
        message: "Le nombre de pages ne peut pas être inférieur ou égal 0"
    )]
    public int $nbPages;
    #[Assert\NotBlank(
        message: "La date de création est obligatoire"
    )]
    public string $dateCreation;
```

Pour vérifier que l'ISBN n'est pas déjà lié à un autre livre, on va utiliser l'EntityManager : 
```php
// Vérifier que l'ISBN n'existe pas déjà
        $repository = $this->entityManager->getRepository(Livre::class);
        if($repository->findOneBy(["isbn" => $requete->isbn])){
            throw new Exception("L'ISBN est déjà lié à un autre livre");
        }
```


### Enregistrement dans la base de données : 
Le livre doit être ajouter dans la base de données une fois que les données sont validés.
```php
$livre = new Livre();   
$livre->setTitre($requete->titre);
$livre->setAuteur($requete->auteur);
$livre->setIsbn($requete->isbn);
$livre->setNbPages($requete->nbPages);
$livre->setDateCreation($requete->dateCreation);
$livre->setDureeEmprunt($livre->getDureeEmprunt());
//Un média qui vient d'être crée doit avoir le statut NOUVEAU
$livre->setStatut($this->constantes->_STATUT_NOUVEAU);
$this->entityManager->persist($livre);
$this->entityManager->flush();
```

## Étapes :

1. Le bibliothécaire va saisir les informations du livre en exécutant la commande :     
```php app.php bibliotheque:livre:create```
2. Le programme vérifie au fur et à mesure de la saisie la validité des données.
3. Le programme va ensuite insérer les enregistrements dans la base données.

## Tests :

* Le bibliothécaire doit saisir des informations valides : elles doivent être renseignés.
* Le programme va contrôler l'unicité de l'ISBN en se servant du validator.
* Le livre est bien ajouté à la base de données.
