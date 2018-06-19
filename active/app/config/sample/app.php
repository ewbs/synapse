<?php

/**
 * Sample config
 * Renommez ce dossier à votre guise et déclarez le dans /app/bootstrap/start.php
 */

return array (
		
		'debug' => true,
		'url' => 'https://synapse.local/',
		'locale' => 'fr',
		'timezone' => 'Europe/Brussels',
		'key' => '[générez une clé avec artisan ou de toute pièce]',
		'providers' => append_config ( array(
        		/* uniquement en dev : commentez en prod & staging ! */
        		'Way\Generators\GeneratorsServiceProvider', // Generators
				'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
				'Barryvdh\Debugbar\ServiceProvider',
			) // IDE Helpers
 		),

		
		'nostra'=>[
			'mail'=>'nom@mail.com'
		],
)
;

