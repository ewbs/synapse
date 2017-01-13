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

Crééz un répertoire (le nom importe peu) sur votre machine hôte. Par exemple "synapse".
Dans ce répertoire, crééz deux sous répertoires "html" et "storage" (respectez ces noms, tout en minuscule sinon vous devrez adapter les scripts Vagrant vous même).
La structure de dossier doit être la suivante

    /synapse
        /html
        /storage
        
### 1.1.3. Génération des clés SSH

Lorsque la VM Vagrant sera lancée, vous pourrez vous y connecter en SSH à l'aide d'une clé privée.
Si vous n'en possédez pas encore, exécuter la commande suivante :

    ssh-keygen -t rsa -C "you@homestead"
    
Si vous êtes sous Windows, vous pouvez utiliser GitBash ou Puttygen pour générer une clé.

### 1.1.4. Récupération du code

Cloner le repository dans le dossier "html" sans créer de sous dossier. Pour cela, ajouter un point en fin de la commande *git clone*.

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

Définissez un récepteur pour les mails de demande "nostra"

    'nostra'=>[
    		'mail'=>'mail@host.com'
    ],
    
#### html/app/config/homestead/mail.php

Définissez un mailrelay pour l'envoi de mails depuis l'application
Suivez les instructions présentes dans le fichier.

#### Fichier hosts

L'application Synapse sera lancée dans une VM ayant l'ip définie dans le fichier homestead.yaml, avec comme dns "synapse.app".
Vous devez définir ceci dans le fichier *hosts* de votre machine.

    192.168.10.10 synapse.app
    
### 1.1.6. Démarrage de la VM

Déplacez vous dans le réperoire "html" et exécutez la commande

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

Depuis le répertoire "html", lance

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

Les scripts Vagrant sont basés et utilise la vagrant box de Laravel/Homestead 
https://github.com/laravel/homestead

Les logos de l'application sont disponibles sur Github : https://github.com/ewbs/synapse-logo
