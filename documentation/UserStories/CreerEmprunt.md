# Documentation - Emprunter un Média

Bienvenue dans la documentation de la fonctionnalité "Emprunter un Média" du système de gestion de bibliothèque. Cette section vous guidera à travers le processus d'emprunt d'un média disponible pour un adhérent.

## User Story
**En tant que bibliothécaire, vous pouvez enregistrer un emprunt de média disponible pour un adhérent afin de permettre à l’adhérent d’utiliser ce média pour une durée déterminée.**

### Comment Utiliser la Fonctionnalité
1. **Vérifier l'Existence du Média :**
    - Assurez-vous que le média que vous souhaitez emprunter existe dans la base de données.
    - Vérifiez que le média est actuellement disponible.

2. **Vérifier l'Existence de l'Adhérent :**
    - Assurez-vous que l'adhérent pour lequel vous souhaitez enregistrer l'emprunt existe dans la base de données.
    - Vérifiez que l'adhésion de l’adhérent est valide.

3. **Enregistrement de l'Emprunt :**
    - Utilisez la commande dédiée pour enregistrer l'emprunt du média. Vous aurez besoin de l'identifiant du média et du numéro d'adhérent de l'emprunteur.
    - Un numéro d'emprunt sera généré automatiquement dans le format “EM-999999999”. Ce numéro unique sera incrémenté à chaque nouvel emprunt.

4. **Vérifier le Statut du Média :**
    - Après l'enregistrement de l'emprunt, vérifiez que le statut du média est maintenant "Emprunté".

(NB : Des tests seront effectués afin de s'assurer que toutes les données requises saisies soit correctes et renseigné)

### Exemple d'Utilisation

1. Vérifier l'Existence du Média
   - Assurez-vous que le média avec l'id 1 est disponible.

2. Vérifier l'Existence de l'Adhérent
   - Assurez-vous que l'adhérent avec le numéro 1 existe et a une adhésion valide.

3. Enregistrement de l'Emprunt
   - Utilisez la commande : `php app.php `
   - Un numéro d'emprunt sera généré automatiquement.

4. Vérifier le Statut du Média
   - Confirmez que le média avec l'id 1 est maintenant "Emprunté".