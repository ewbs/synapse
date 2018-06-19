<?php
return array(

	/*
	|--------------------------------------------------------------------------
	| Default Queue Driver
	|--------------------------------------------------------------------------
	|
	| The Laravel queue API supports a variety of back-ends via an unified
	| API, giving you convenient access to each back-end using the same
	| syntax for each one. Here you may set the default queue driver.
	|
	| Supported: "sync", "beanstalkd", "sqs", "iron"
	|
	*/

	'default' => 'async',

	/*
	|--------------------------------------------------------------------------
	| Queue Connections
	|--------------------------------------------------------------------------
	|
	| Here you may configure the connection information for each server that
	| is used by your application. A default configuration has been added
	| for each back-end shipped with Laravel. You are free to add more.
	|
	*/

	'connections' => array (
		'sync' => array (
			'driver' => 'sync' 
		),
		
		/**
		 * Utilisation du driver async, correspondant au plugin https://github.com/barryvdh/laravel-async-queue/tree/v0.3.1
		 *
		 * Va de paire avec le fait d'utiliser les commandes suivantes :
		 * php artisan queue:work async (pour exécuter la tâche suivante)
		 * php artisan queue:work async --daemon  --sleep=300 --tries=3 (pour faire tourner en permanence la tâche qui va traiter tout ce qui rentre en queue)
		 *
		 * Note : Lorsqu'une tâche tombe en failed_job, une fois le souci résolu on peut la remettre dans la queue via php artisan queue:retry #id
		 */
		'async' => array(
			'driver' => 'async',
			'binary' => 'php', //sous-entend que php se trouve dans le PATH
		),
		
		'beanstalkd' => array (
			'driver' => 'beanstalkd',
			'host' => 'localhost',
			'queue' => 'default' 
		),
		
		'sqs' => array (
			'driver' => 'sqs',
			'key' => 'your-public-key',
			'secret' => 'your-secret-key',
			'queue' => 'your-queue-url',
			'region' => 'us-east-1' 
		),
		
		'iron' => array (
			'driver' => 'iron',
			'project' => 'your-project-id',
			'token' => 'your-token',
			'queue' => 'your-queue-name' 
		) 
	),

	/*
	|--------------------------------------------------------------------------
	| Failed Queue Jobs
	|--------------------------------------------------------------------------
	|
	| These options configure the behavior of failed queue job logging so you
	| can control which database and table are used to store the jobs that
	| have failed. You may change them to any database / table you wish.
	|
	*/
	
	'failed' => array (
		'database' => 'pgsql',
		'table' => 'failed_jobs' 
	)
)
;