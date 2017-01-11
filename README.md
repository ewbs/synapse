# synapse
Synapse : Outil de suivi des projets et actions de simplification administrative en Wallonie et Fédération Wallonie-Bruxelles

#Sommaire
[ A ECRIRE ]

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
        
### 1.1.3 Génération des clés SSH

Lorsque la VM Vagrant sera lancée, vous pourrez vous y connecter en SSH à l'aide d'une clé privée.
Si vous n'en possédez pas encore, exécuter la commande suivante :

    ssh-keygen -t rsa -C "you@homestead"
    
Si vous êtes sous Windows, vous pouvez utiliser GitBash ou Puttygen pour générer une clé.

### 1.1.4 Récupération du code

Cloner le repository dans le dossier "html" sans créer de sous dossier. Pour cela, ajouter un point en fin de la commande *git clone*.

    git clone https://github.com/ewbs/synapse.git .
    
### 1.1.5 Edition de la configuration

Editez le fichier *Homestead.yaml* présent à la racine du code (dans "html" donc).
    
    

## 1.2. Dans un environnement existant

[A ECRIRE ]

git clone https://github.com/ewbs/synapse.git .  (avec point)
hosts --> ip machine synapse.app
vagrant up
vagrant ssh donnera les params de connexion (pour putty) --> window : http://www.cnx-software.com/2012/07/20/how-use-putty-with-an-ssh-private-key-generated-by-openssh/





composer install --no-scripts
