# Release Notes

## 5.1.0 - Restore hope 
*Ce release de Synapse permet la gestion des données du plan de dématérialisation.*

### Démarches
- Added : Pouvoir créer une nouvelle démarche dans Synapse indépendamment de Nostra 
- Added : Possibilité de suppression d'une démarche dans Synapse
- Added : Flag indiquant l'incorporation d'une démarche du plan démat
- Added : 4 champs supplémentaires (Volumétrie, Publics cibles, Thématique ABC, Thématique administration)
- Added : Possibilité d'export des démarches à partir de Synapse avec filtres intégrés
- Added : Possibilité de lier automatiquement les formulaires liés à une démarche dans Nostra 
- Added : Possibilité de créer un nouveau formulaire à partir d'une démarche 
- Changed : possibilité de choisir de n'intégrer qu'une partie des formulaires liés à une démarche dans Nostra
- Fixed bug : Impossibilité d'effectuer une recherche de démarche via son ID Nostra

### Formulaires 
- Added : 7 champs supplémentaires (Description, Disponible en ligne, Déposable en ligne, Dématérialisation, Intervention eWBS, Référence Contrat d'administration, Remarques)
- Added : ajout d'un calendrier pour la date souhaitée pour la dématérialisation 
- Added : ajout d'un flag "documenté" lorsque le formulaire a été documenté
- Added : canal de dématérialisation d'une démarche dématérialisée
- Removed : Champ "Annexe" lié aux formulaires 
- Changed : interdiction de supprimer un formulaire s'il est lié à une démarche

### Layout
- Fixed bug : un bouton n'apparaît pas comme sélectionné s'il est activé 

## 4.3.1 - Back in action 2
*Ce release de Synapse consolide les données provenant de Nostra.*

### Imports
- Added : Ne plus stopper la synchro lorsqu'une erreur survient à l'import d'une démarche (l'ignorer), et notifier le RTA par email en cas d'erreurs (cf github #38 & #39)

### Démarches
- Added : Export : Ajout de l'ID Nostra (cf github #70)

## 4.3.0 - Back in action
*Ce release de Synapse intègre principalement diverses améliorations sur le module actions, ainsi que quelques améliorations et corrections mineures.*

### Migrations
- Added : Vue permettant d'obtenir la première révision d'une action
- Fixed bug : Renommer le script de migration des users, car il interfère avec celui qui se trouve dans Confide
- Fixed bug : Ajout clé primaire aux tables correspondant aux filtres utilisateur existants (administration, public, tag)
- Fixed bug : Les clés étrangères créées après un "unsigned() n'étaient en fait pas créées : correction des scripts (attention, va de paire avec un script SQL à exécuter via le queryrunner) - (cf github #40)
- Fixed bug : Clé étrangère sur les publics et thématiques abc/adm vers leur parent (attention, va de paire avec un script SQL à exécuter via le queryrunner) - (cf github #40)

### Monitoring
- Added : Database - Ajout d'un check sur la présence de l'extension btree_gist

### Query Runner
- Fixed bug : Mieux gérer le cas où une requête rend un résultat vide

### Dashboard
- Added : Filtre "par action" dans la fonction "Mes filtres"
- Added : Application du filtre "par action" sur mes projets de simplif'
- Added : Application du filtre "par action" sur mes démarches
- Added : Application du filtre "par action" sur mes formulaires
- Added : Application du filtre "par action" sur mes actions
- Added : Filtres sur mes actions "Celles que j’ai créées" et "Celles qui me sont assignées"
- Added : Colonnes "Priorité", "Assignation" et "Révision" sur la liste de mes actions
- Added : Application du filtre "par action" sur mes charges administratives
- Changed : Désactivation du filtre "par tag"
- Changed : Présenter les actions en cours par pôle et expertises dans "Mon dashboard"
- Changed : Retrait de la colonne "Sous-actions" sur la liste de mes actions
- Changed : Améliorations de l'affichage de mes charges administratives (valeurs en badges)
- Fixed bug : Les filtres ne s'appliquaient pas sur les pièces et tâches dont le total est présenté dans le pavé "Catalogue des démarches"

### Actions
- Added : Nouvel état "En standby"
- Added : Notion d'assignation d'une action à un user (édition, détail, liste)
- Added : Filtres par assignations, noms/types, administrations sur la liste des actions
- Changed : Etat "Initialisé" renommé en "A faire"
- Changed : Choix du nom d'une action limité à la liste des expertises
- Changed : Retrait de la colonne "Sous-actions" sur la liste des actions

### Démarches
- Added : Notion d'assignation d'une action à un user (édition & liste d'actions, création d'action suite à édition de pièces/tâches/formulaires)
- Added : ID Nostra dans le détail d'une démarche
- Changed : Choix du nom d'une action limité à la liste des expertises
- Changed : Retrait de la colonne "Sous-actions" sur la liste des actions
- Changed : Présenter les actions en cours par pôle et expertises dans le détail d'une démarche

### Expertises & pôles
- Added : Création des nouveaux modèles de données et remplissage avec les valeurs actuelles

### Formulaires
- Added : Notion d'assignation d'une action à un user (création, édition & liste d'actions)
- Changed : Choix du nom d'une action limité à la liste des expertises

### Projets
- Fixed bug : Les actions directement liées à un projet n'étaient pas affichées dans le détail

### Users
- Added : Nouveau modèle de données "Filtre utilisateur par expertise"
- Fixed bug : Adaptation des la construction des routes, suppression de code non utilisé, ajout d'un message manquant

## 4.2.0 - Ministry of Silly Walks
*Ce release de Synapse intègre principalement les interfaces de gestion des ministres, ainsi que quelques améliorations et corrections mineures.*

### Imports
- Fixed bug : Erreur d'import Nostra sur les thématiques abc/adm/publics lorsqu'ils n'ont pas de parent (bug introduit par du refactoring de code)

### Général
- Added : Permettre de présenter  par défaut un datatable ajax en ordre descendant sur la 1e colonne
- Added : Implémentation d'un query runner
- Changed : Présenter les champs datepicker en format yyyy-mm-dd

### Démarches
- Changed : Ajouter la notion de formulaires dans le bouton "Pièces et tâches" dans les écrans de traitement d'une démarche
- Changed : Externaliser le javascript exécuté dans la liste des démarches
- Fixed bug : Lors de la MAJ de l'état d'un projet après traitement d'une pièce ou tâche, filtrer les états selon les droits de l'utilisateur
- Fixed bug : Permettre de retirer un public ou une administration des listes de filtres après rechargement de la page de liste des démarches

### Ministres
- Added : Interfaces de visualisation et de gestion des ministres et de leurs mandats, pour les administrateurs

### Projets
- Changed : Rendre directement l'état éditable dans l'édition d'un projet
- Changed : Refactoring et documentation de la partie gérant les états des projets

## 4.1.2 - Never forget (04/04/2017)
*Release contenant le backport du query runner.*

### Général
- Added : Implémentation d'un query runner

## 4.1.1 - Never forget (07/03/2017)
*Release correctif suite à un souci sur l'import des données Nostra.*

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
