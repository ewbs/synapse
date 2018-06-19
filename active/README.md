# ![logo synapse](https://raw.githubusercontent.com/ewbs/synapse/master/public/images/logo.png) synapse
Synapse : Outil de suivi des projets et actions de simplification administrative en Wallonie et Fédération Wallonie-Bruxelles

#Sommaire

1. Instructions d'installation
    - 1.1. Sous Vagrant (conseillé)
        - 1.1.1. Prérequis
        - 1.1.2. Création de la structure de fichiers
        - 1.1.3. Génération des clés SSH
        - 1.1.4. Récupération du code
        - 1.1.5. Edition de la configuration
        - 1.1.6. Démarrage de la VM
        - 1.1.7. Go!
    - 1.2. Dans un environnement existant
2. Utilisation de l'environnement
    - 2.1. Répertoires partagés
        - 2.1.1. /html
        - 2.1.2. /storage
    - 2.2. Accès SSH
    - 2.3.Forwards de ports
3. Licence
4. Informations additionnelles
        

# 1. Instructions d'installation

## 1.1. Sous Vagrant (conseillé)

### 1.1.1. Prérequis

Avant de cloner ce repository, vous aurez besoin de :
- VirtualBox
- Vagrant

Ces deux composants vont vous permettre de démarrer Synapse dans une machine virtuelle.
Il n'est donc pas nécessaire d'installer un serveur web, php ou un SGBD sur votre machine.

### 1.1.2. Création de la structure de fichier

Créez un répertoire (le nom importe peu) sur votre machine hôte. Par exemple "synapse".
Dans ce répertoire, créez deux sous répertoires "html" et "storage" (respectez ces noms, tout en minuscule sinon vous devrez adapter les scripts Vagrant vous même).
La structure de dossier doit être la suivante :

    /synapse
        /html
        /storage
        
### 1.1.3. Génération des clés SSH

Lorsque la VM Vagrant sera lancée, vous pourrez vous y connecter en SSH à l'aide d'une clé privée.
Si vous n'en possédez pas encore, exécuter la commande suivante :

    ssh-keygen -t rsa -C "you@homestead"
    
Si vous êtes sous Windows, vous pouvez utiliser GitBash ou Puttygen pour générer une clé.

### 1.1.4. Récupération du code

Clonez le repository dans le dossier "html" sans créer de sous dossier. Pour cela, ajoutez un point en fin de la commande *git clone*.

    git clone https://github.com/ewbs/synapse.git .
    
### 1.1.5. Edition de la configuration

#### Homestead.yaml

Editez le fichier *Homestead.yaml* présent à la racine du code (dans "html" donc).
Effectuez les modifications suivantes :

##### IP de la VM

    ip:"192.168.10.10"
    
##### Clés SSH

Sous Linux :

    authorize: ~/.ssh/id_rsa.pub
    keys:
        - ~/.ssh/id_rsa
    
Sous Windows :

    authorize: ~/ssh/homestead.pub
    keys:
        - ~/ssh/homestead
    
(ceci indique que la clé se trouve dans un dossier "ssh" situé dans le profil de l'utilisateur (\users\nom\...)
    
##### Répertoires

    folders:
        - map: "C:/foo/bar/html"
          to: "/home/vagrant/html"
        - map: "C:/foo/bar/storage"
          to: "/home/vagrant/storage"
          
Vos répertoires locaux *html* et *storage* seront montés dans la VM.
Les modifications sont bidirectionnelles (hote <--> vm).

#### html/app/config/homestead/app.php

Définissez un récepteur pour les mails de demande "nostra" :

    'nostra'=>[
    		'mail'=>'mail@host.com'
    ],
    
#### html/app/config/homestead/mail.php

Définissez un mailrelay pour l'envoi de mails depuis l'application. Suivez les instructions présentes dans le fichier.

#### Fichier hosts

L'application Synapse sera lancée dans une VM ayant l'ip définie dans le fichier homestead.yaml, avec comme dns "synapse.app".
Vous devez définir ceci dans le fichier *hosts* de votre machine.

    192.168.10.10 synapse.app
    
### 1.1.6. Démarrage de la VM

Déplacez-vous dans le réperoire "html" et exécutez la commande :

    vagrant up
    
Vagrant va télécharger une box de base et installer l'application.

### 1.1.7. Go!

Lancez un navigateur et consultez :

    https://synapse.app
    
Vous pouvez vous connecter avec les credentials admin/admin.

## 1.2. Dans un environnement existant

[ A ECRIRE ]

# 2. Utilisation de l'environnement

## 2.1. Répertoires partagés

Vos dossiers "html" et "storage" sont montés dans la VM. Toute modification est bidirectionnelle.

### 2.1.1. /html

Contient le code applicatif. Vous pouvez y démarrer un projet avec votre IDE favori et commencer à coder.

### 2.1.2. /storage

Contient tous les fichiers générés par l'application, y compris les logs.

## 2.2. Accès SSH

Depuis le répertoire "html", lancez :

    vagrant ssh
    
Sous linux, vous obtiendrez un shell dans la VM.
Sous Windows, vous obtiendrez les paramètres de connexion, à entrer dans votre client SSH favori (Putty par exemple).

Le login a utiliser est **vagrant**.

**Remarque importante:** sous windows, la clé pourrait être refusée.
Pour corriger le problème, suivez ce tuto : http://www.cnx-software.com/2012/07/20/how-use-putty-with-an-ssh-private-key-generated-by-openssh/

## 2.3. Forwards de ports

Plusieurs ports sont forwardés de l'hôte vers la VM :

- 2222 (hote) --> 22 (vm) -- pour vous connecter en SSH
- 80000 (hote) --> 80 (vm)
- 54320 (hote) --> 5432 (vm) -- pour administrer la base de données avec un outil comme PGAdmin.

# 3. Licence

Synapse est distribué sous licence GNU/GPLv3.

Ce logiciel contient différents composants distribués sous licence MIT :
- Laravel 4.2
- Twitter Bootstrap
- jQuery

Cette application a été développée par e-Wallonie-Bruxelles-Simplification; organe de Simplification Administrative en Wallonie et en Fédération Wallonie-Bruxelles (Belgique)
http://www.ensemblesimplifions.be

# 4. Informations additionnelles

Synapse est basé sur le Startersite de Andrew Welkins (https://github.com/andrewelkins/Laravel-4-Bootstrap-Starter-Site)

Les scripts Vagrant exploitent la vagrant box de Laravel/Homestead
https://github.com/laravel/homestead

Les logos de l'application sont disponibles sur Github : https://github.com/ewbs/synapse-logo

# 5. Principales particularités techniques de Synaspse

## 5.1. Couche objet pour représenter les modèles gérés dans l'application
Une couche complémentaire a été écrite par dessus les modèles & contrôleurs, afin de définir des :
- **ManageableModel :** Modèles auquelles corespondent des fonctionnalités de type list, create, update, view, delete
- **TrashableModel :** Modèles liés en plus à une gestion de corbeille.

A ces modèles correspondent des contrôleurs (ModelController & TrashableModelController), permettant d'avoir une logique commune entre tous ces modèles.  
A noter que ces modèles héritent de Ardent plutôt que Eloquent, afin de bénéficier des possibilités de validateurs offertes par Ardent.

## 5.2. Historisation de certains contenus

Certaines tables ou tables de liaison sont historisées, cela signifie qu'à chaque modification effectuée, une insertion est effectuée dans la table.
Cela a été effectué de 2 manière différentes :

### 5.2.1. La table contient directement tout son historique

C'est le cas de :
- demarche_eform (associée à la vue v_lastrevisiondemarcheeform)

Dans ce cas, la vue reprend la dernière révision de la table, pour faciliter les requêtes à effectuer.

### 5.2.2. La table de base est liée à une seconde table reprenant toutes les données à historiser sur ce contenu

C'est le cas de :
- demarches + demarchesRevision (associées à la vue v_lastrevisionfromdemarche)
- demarche_demarchePiece + demarche_demarchePiece_revisions (associées à la vue v_lastrevisionpiecesfromdemarche)
- demarche_demarcheTask + demarche_demarcheTask_revisions (associées à la vue v_lastrevisiontasksfromdemarche)
- eforms + eformsRevision (associées à la vue v_lastrevisioneforms)
- ewbsActions + ewbsActionsRevisions (associées à la vue v_lastrevisionewbsaction)

Dans ce cas, la vue reprend la dernière révision de la table historisée + les données de la table principale, pour faciliter les requêtes à effectuer.

# 5.3. Vues particulières

Outre les vues évoquées dans le point précédent sur les données historisées, 2 vues ont été nécessaires pour faciliter le calcul et l'affichage des gains des démarches :

- **v_calculateddemarchegains :** afin d'obtenir les gains calculés par démarche (calculés = déduits des pièces et tâches liées)

 - **v_demarchegains :** afin d'obtenir les gains soit encodés directement dans la dernière révision de la démarche, ou à défaut de retomber sur le gain calculé
 
 - **v_firstrevisionewbsaction :** afin d'obtenir la première révision d'une action (notamment pour déterminer l'auteur de l'action)

## 5.4. Consolidation des données Nostra au sein de Synapse

Plusieurs types de contenus sont importés depuis Nostra, il s'agit de :
- démarches
- documents
- événements
- formulaires
- publics
- thématiques abc
- thématiques administration

La routine d'import des données (importFromNostraV2) permet de compléter les tables correspondantes.  
Parmi ces types de contenus, 2 d'entre eux nécessitent des données complémentaires, gérées alors dans des tables spécifiques de Synapse :

### 5.4.1. Démarches

La table "nostra_demarches" est complétée par la table "demarches".  
Il est à noter qu'il n'est pas possible de créer une démarche sans avoir une nostraDémarche correspondante.  
L'action à effectuer est de documenter une "nostraDémarche", ce qui permet d'en dériver une "démarche".

### 5.4.2. Formulaires

La table "nostra_forms" est complétée par la table "eforms".  
Contrairement aux démarches, il est ici possible de créer un "eform" (un formulaire Synapse donc), sans qu'il n'y a de "nostraForm" correspondant.  
Cela correspondait à un besoin de pouvoir travailler dans Synapse sur un formulaire, alors qu'il n'était pas encore présent dans Nostra.

Cela débouche sur un mécanisme de "réconciliation", permettant de proposer à l'utilisateur de lier un "eform" à un "nostraForm" lorsqu'il décide de documenter un "nostraForm" (documenter = dériver un eform depuis un "nostraForm").  
NB : Cette réconciliation se fait sur la correspondance du champ slotId.

## 5.5. Javascript : datatables, modales serveur & select2

Une certaine généricité a été instaurée pour faciliter l'usage de ces mécanismes, et compléter les possibilités de base.  
Le code javascript correspondant aux précisions se trouve ddans public/js/behaviour/general.js

### 5.5.1 Pour un datatable

Il suffit qu'un tableau ait une classe "datatable", et définisse les attributs html5 suivants :

- **data-ajaxurl :** Url qui sera utilisée par le composant pour charger des données json générées par le serveur
- **data-bfilter :** Activer le champ de recherche pour filtrer le tableau, false par défaut
- **data-bpaginate :** Paginer les réultats, false par défaut
- **data-bsort :** Activer le tri sur les en-têtes de colonnes, false par défaut
- **data-desc :** Trier par défaut de manière descendante sur la 1e colonne; à défaut, le tri sera ascendant sur la 1e colonne
- **data-useform :** Lier le datatable à un formulaire, afin d'utiliser les champs du formulaire comme filtres sur le tableau (avec gestion automatique du passage des paramètres à l'url ajax et rechargement des résultats). La valeur de cet attribut sera une expression permettant de cibler le formulaire, par exemple *data-useform="#monSuperForm"* ou *data-useform=".monJoliForm"*

### 5.5.2 Pour une modale serveur

Afin de déclencher l'ouverture d'une modale dont le contenu sera généré côté serveur, il suffit de définir une classe "servermodal" sur un élément "a" ou "button".  
Le clic sera alors intercepté, et l'attribut href sera utilisé pour effectuer une requête ajax, et charger le résultat dans une modale.

Ce type de modale est surtout utilisé pour proposer un formulaire de création/édition d'un contenu.  
Un mécanisme est donc prévu pour que la soumission du formulaire se trouvant dans la modale se fasse également en ajax, et que le résultat recharge la modale (et à défaut d'un résultat, la modale est refermée).

A différents endroits de l'application, des fonctions d'édition ou de suppression sur des listes générées en datatables sont proposées. Effectuer cette action impacte le contenu du tableau (dont les colonnes peuvent changer, ou une ligne peut être supprimée).  
L'attribut html5 "data-reload-datatable" positionné sur le formulaire permet de le lier à un datatable, avec pour impact de recharger le contenu du tableau lorsque la modale contenant le formulaire est refermée.  
La valeur de cet attribut sera une expression permettant de cibler le datatable, par exemple *data-reload-datatable="#monSuperTableau"* ou *data-reload-datatable=".monJoliTableau"*

### 5.5.3 Pour un select2

La possibilité a été donnée de spécifier un affichage sur maximum 3 lignes par résultat, ainsi que d'une image.

Pour cela, spécifier les attributs html5 suivants :
- **data-line2 :** Seconde ligne optionnelle de contenu
- **data-line3 :** Troisème ligne optionnelle de contenu
- **data-picture :** Image à afficher en regard du texte
- **data-picturewidth :** Taille de l'image, par défaut 40px

Le comportement défini est le suivant :
- Si aucun attribut n'est spécifié, le texte est retourné sans aucun formatage
- Si l'image est renseignée, elle est placé en regard du texte, entouré d'un div class="line1"
- Si line2 ou line3 sont renseignés, ils sont placés sous la 1e ligne affichant le texte de base (entouré alors d'un div class="line1 title"), eux-mêmes étant entourés d'un div class="linex"

Les classes line1, line2, line3, title sont définies en css.

## 5.6. Migrations

### 5.6.1. Suffixe des scripts de migration

A partir du release 4.0, les scripts ont été suffixés par la version correspondant au release (exemple 2017_09_12_101359_update_forms_tables_44.php).  
L'objectif est d'éviter les conflits de nommage au niveau objet (car contrairement au nom du fichier qui est préfixé de la date, le nom de la classe reprend seulement le nom donné au script).

### 5.6.2. Exécution dans une seule transaction

Laravel ne prévoit pas de commandes de migration et rollback permettant l'exécution des scripts dans une seule transaction. Cata garantie quand l'exécution se plantait en plein milieu d'une migration...
 
2 commandes ont donc été ajoutées afin de compléter les commandes de base, elles sont accessibles via migrate:transaction et migrate:rollback:transaction.

## 5.7. Gestion d'une queue
L'envoi de mail a été implémenté via une queue, en se basant sur le plugin laravel-async-queue de barryvdh.  
Ce plugin était cependant incomplet, si bien qu'un fork a été créé, et c'est ce fork qui est exploité dans le cadre de l'application.  
Le fork est présent sur [https://github.com/ewbs/laravel-async-queue](https://github.com/ewbs/laravel-async-queue)
