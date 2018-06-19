<?php
return array(

    /*
     * Editez ces paramètres
     * debug : sortie des erreurs : false, sauf sur une machine de dev
     * url : url complète de l'application
     */
    
    'debug' => false,
		'url' => 'https://synapse.test.wallonie.be/',
    
    /*
     * Ne changez rien à partir d'ici
     */
    
    'locale' => 'fr',
		'timezone' => 'Europe/Brussels',
		'key' => 'jWSewz3Pfx1lLkrXAgFeCfDdsF4bZ8fd',
		'providers' => append_config ( array(
        /* uniquement en dev : commentez en prod & valid ! */
        /*'Way\Generators\GeneratorsServiceProvider', // Generators
        'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider', // IDE Helpers*/
        ) ),

	'nostra'=>[
		'mail'=>'jda@ewbs.be'
	],
)
;
