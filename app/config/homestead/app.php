<?php

/**
 * Config homestead
 */

return array (
		
		'debug' => true,
		'url' => 'https://synapse.app/',
		'locale' => 'fr',
		'timezone' => 'Europe/Brussels',
		'key' => 'ZZZewz3Pfx1lLkrXAgFeCfDdsF4bZ8fd',
		'providers' => append_config ( array(
        		/* uniquement en dev : commentez en prod & staging ! */
        		'Way\Generators\GeneratorsServiceProvider', // Generators
				'Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider',
				'Barryvdh\Debugbar\ServiceProvider',
			) // IDE Helpers
 		),

		
		'nostra'=>[
			'mail'=>'mail@host.com'
		],
)
;

