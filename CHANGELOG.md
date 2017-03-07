# Release Notes

## 4.1.1 - Never forget (07/03/2017)
* Release correctif suite à un souci sur l'import des données Nostra.*

### Imports
- Fixed bug : Une partie de la routine d'import Nostra était désactivée. Refactoring du code, afin notamment d'utiliser GuzzleHttp\Client plutôt que curl

### Général
- Fixed bug : Adaptations mineures du niveau de qualité de code suite à audit du code par SonarQube
- Fixed bug : Ne pas tenter d'activer des plugins JS sur des éléments absents du dom

## 4.1.0 - Never forget (22/02/2017)
*Ce release de Synapse, le premier après le départ de son papa, apporte essentiellement des corrections sur le release 4.0, ainsi que des améliorations mineures.*

### Imports
- Added : Désactiver dans Synapse les démarches qui côté Nostra ne sont plus associées à Synapse

### Général
- Changed : Nouveau format de changelog
- Changed : Nouvelle homepage
- Changed : Remonter les features dans le bandeau supérieur, à côté du titre
- Changed : Centraliser la génération des titres de pages
- Changed : Harmoniser entre "Editer" et "Modifier"
- Changed : Harmoniser les boutons de soumission / annulation
- Changed : Toujours construire une url via le nom de sa route (remplacer les anciennes définitions)
- Changed : Remplacer les anciennes définitions javascript des datatables par les paramètres data-* passés au niveau du table
- Fixed bug : Menu principal : Garder l'élément sélectionné à tout moment lorsqu’on est dans ce point de menu
- Fixed bug : Menu principal : Affichage d'un ascenseur si des éléments passent hors écran
- Fixed bug : Lien de retour à la liste ne fonctionnait pas toujours (constaté dans les taxonomies et services)
- Fixed bug : Résoudre l'absence de la colonne deleted_at de la table user dans certains environnements
- Fixed bug : Spécifier la mention "factultatif" pour tous les champs concernés et centraliser cet affichage
- Removed : Vues inutilisées

### Admin
- Changed : Refactoring de la gestion des utilisateurs (héritage des ManageableModel, corbeille et restauration des utilisateurs)

### Dashboard
- Added : Pouvoir faire un export excel à partir du tableau des charges administratives

### Démarches
- Fixed bug : Détection d'incohérence entre nostraforms et eforms + suggestion de formulaires nostra lors de de la liaison d’un formulaire à une démarche
- Fixed bug : Ne pas considérer les projets supprimés lors de la liaison entre démarches et projets

### Formulaires
- Fixed bug : Contraintes sur le titre et commentaire ne sont pas affichées à la sauvegarde d'une action

### Pièces
- Fixed bug : Permettre de supprimer une pièce ou tâche du catalogue lorsqu'elle n'est pas liée à une démarche

### Projets
- Added : Automatiquement lier la démarche au projet via la fonction "Lier un projet à ma démarche"
- Changed : Encoder un relai eWBS devrait devenir facultatif
- Fixed bug : Souci affichage de la colonne état dans la liste des projets de simplif, lorsqu'état est vide

### Tags
- Fixed bug : Permettre de sauvegarder un service sans tag spécifié



## 4.0.0 - Challenge accepted (18/01/2017)
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans les documents de sprint 6, 9, 11, 12.*

### Général
- Changed : Refonte de nombreuses interfaces de listings et détails (notamment vers un affichage en blocs)
- Fixed bug : Limiter le menu "Admin" au rôle admin

### Dashboard
- Added : Notion de filtres
- Added : Mise en avant des différents types de contenus affichés selon les filtres sélectionnés

### Actions
- Added : Notions de sous-actions & priorité

### Démarches
- Added : Permettre le tri sur la liste des démarches
- Added : Lister les formulaires et documents dans l'export xls des démarches
- Changed : Ne plus réexécuter la requête de liste des démarches lors de l'export, se baser sur celles du listing
- Changed : Amélioration de la liste de sélection des formulaires (liaison d'un formuaire à la démarche)
- Fixed bug : Adaptations de l'import/export SCM pour compatibilité des montants décimaux selon le tableur utilisé

### Formulaires
- Added : Réconciliation entre eForm et NostraForm lors de la redescente d'un NostraForm vers Synapse

### Projets
- Changed : Modification de l'encodage d'un projet (lien avec démarche)

### Services
- Added : Notion de catalogue de services

### Tags
- Added : Notions de taxonomie et synonymie



## 3.1.0 - Into the trash it goes (19/12/2016)
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans les documents de sprint 10.*

### Actions
- Fixed bug : Incohérence entre le champs deleted_at de l'action et de ses révisions

### Démarches
- Added : Permettre de lier plusieurs fois le même composant (pièce, tâche) à la meme démarche
- Changed : Gains effectifs des pièces et tâches à 0 par défaut
- Fixed bug : Adaptations de l'export SCM pour compatibilité des montants avec spéarateurs de milliers



##3.0.0 - Draw me like one of your french girls (26/10/2016)
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans les documents de sprints 7 et 8.*

### Général
- Added : Bloquer le changement de page dans les formulaires de création / édition
- Changed : Optimisations de nombreuses requêtes SQL
- Changed : Déplacer le storage en dehors du répertoire de l'application

### Actions
- Changed : Notion de "démarche action" renommée massivement en "eWBS action"

### Damus
- Added : Vue circulaire sur les données provenant de NOSTRA

### Démarches
- Added : Remontée d'info vers NOSTRA au travers d'actions
- Added : Création d'un projet de simplif à partir d'une démarche
- Fixed bug : Erreur édition démarche si liens documentation vides

### Formulaires
- Added : Notion de formulaires et annexes en lien avec actions et démarches

### Projets
- Added : Remontée d'info vers NOSTRA au travers d'actions
- Fixed bug : Impossibilité de modifier le statut d'un projet
- Fixed bug : Disparition bouton "modifier l'état"



##2.0.0 - That'd be great (02/05/2016)
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans les documents de sprints 2 et 4.*

### Actions
- Added : Notion d'actions en lien avec des pièces et tâches liées à des démarches

### Admin
- Added : Gestion des administrations

### Démarches
- Added : Liens de documentation sur les démarches
- Changed : Largeur des colonnes restreinte à la génération des SCM Light

### Pièces et données
- Added : Gestions des pièces et des données

### Projets
- Added : Liaison entre démarches et projets



## 1.1.0 - Zerg Rush (27/10/2015
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans le document de sprint 3.*

### Projets
- Added : Notions de commentaires et états et gestion des rôles



## 1.0.0 - Nyan Cat (19/10/2015)
*Ce release reprend principalement les fonctionnalités évolutives spécifiées dans le document de sprint 1.*

### Général
- Added : Première version de l'application déployée

### Imports
- Added : Intégration de NOSTRA v2
