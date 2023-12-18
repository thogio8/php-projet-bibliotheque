# Documentation - Rendre Disponible un Nouveau Média

## User Story
**En tant que bibliothécaire, je veux rendre disponible un nouveau média afin de le rendre empruntable par les adhérents de la bibliothèque.**

## Description
Cette fonctionnalité permet aux bibliothécaires de rendre disponible un nouveau média, facilitant ainsi son emprunt par les adhérents de la bibliothèque. L'accès au média se fait via son identifiant unique.

## Commande
```bash
php app.php bibliotheque:media:update:disponible
```

## Critères d’acceptation
1. **Média existe :**
    - Le média que l’on souhaite rendre disponible doit exister.

2. **Statut du média :**
    - Seul un média ayant le statut “Nouveau” peut être rendu disponible.

3. **Enregistrement dans la base de données :**
    - Le changement de statut du média doit être correctement enregistré dans la base de données.

4. **Messages d’erreurs :**
    - Des messages d'erreur explicites sont retournés en cas d'informations manquantes ou incorrectes.

## Exemple de sortie en format console

```markdown
Le média a été rendu disponible avec succès.
```


