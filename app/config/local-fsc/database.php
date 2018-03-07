<?php
return array(
	'fetch' => PDO::FETCH_CLASS,
	'default' => 'pgsql',
	'connections' => array (
		'pgsql' => array (
			'driver' => 'pgsql',
			'host' => 'localhost',
			'database' => 'synapse',
			'username' => 'synapse',
			'password' => 'synapse',
			'charset' => 'utf8',
			'prefix' => '',
			'schema' => 'ewbs'
		)
	),
	'migrations' => 'migrations',
	'redis' => array (
		'cluster' => false,
		'default' => array (
			'host' => '127.0.0.1',
			'port' => 6379,
			'database' => 0
		)
	)
);