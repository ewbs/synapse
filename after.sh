#!/bin/sh


if [ ! -f /usr/local/synapse_installed ]; then
	echo 'Installation de Synapse'

    #
    # installation de l'extension PHP-MBString
    #
    echo '... Installation de PHP MBString & MCrypt'
    sudo apt-get update
    sudo apt-get install -y php-mbstring
    sudo apt-get install -y php-mcrypt
    sudo service php5-fpm restart
    sudo service nginx restart

    #
    # installation de Synapse
    #
    echo '... Création du storage'
    sudo mkdir /home/vagrant/storage/cache
    sudo mkdir /home/vagrant/storage/logs
    sudo mkdir /home/vagrant/storage/meta
    sudo mkdir /home/vagrant/storage/sessions
    sudo mkdir /home/vagrant/storage/views
    sudo mkdir /home/vagrant/storage/uploads
    sudo mkdir /home/vagrant/storage/uploads/scm
    sudo chmod -R 0777 /home/vagrant/storage/

    echo '... Modification du public'
    sudo chmod -R 0777 /home/vagrant/html/public/

    echo '... Execution de la migration'
    cd /home/vagrant/html
    composer install --no-scripts
    echo '... Préparation de la DB avec des données de test'
    php artisan migrate --seed
    echo '... Importation des données Nostra'
    php artisan cron:importFromNostraV2
    echo '... Création du cron mailer'
    crontab < <(crontab -l ; echo "* * * * * vagrant /usr/bin/php /home/vagrant/html/artisan queue:work async > /dev/null")
    sudo service cron restart
    echo '... Démarrage de Synapse'
    php artisan up


    #
    # marquer ce fichier comme exécuté
    #
    sudo touch /usr/local/synapse_installed
else
    echo "Synapse est déjà installé"
fi