# Documentation - Liste des Nouveaux Médias

## User Story
**En tant que bibliothécaire    
Je veux lister les nouveaux médias  
Afin de les rendre disponibles aux adhérents de la bibliothèque.**


## Description
Cette fonctionnalité permet aux bibliothécaires de lister les nouveaux médias.
## Commande
```bash
php app.php bibliotheque:media:select:new
```


## Critères d’acceptation
1. **Valeurs retournées :**
    - **ID du média:** Identifiant unique du média.
    - **Titre du média:** Le titre du média.
    - **Statut du média:** Le statut actuel du média (Disponible, Nouveau, Emprunté, Non disponible).
    - **Date de création:** La date à laquelle le média a été ajouté à la base de données.
    - **Type du média:** Le type du média (livre, bluray ou magazine).

2. **Ordre de tri :**
    - La liste doit être triée par date de création, de la plus récente à la plus ancienne.

## Exemple de sortie en format table

| ID du Média | Titre du Média | Statut         | Date de Création | Type du Média |
|-------------|----------------|----------------|------------------|---------------|
| 1           | Livre A        | Nouveau        | 2023-01-15       | Livre         |
| 2           | Magazine B     | Nouveau        | 2023-01-10       | Magazine      |
| 3           | Bluray C       | Nouveau        | 2023-01-05       | Bluray        |
