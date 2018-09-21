<?php
return array(
	'driver' => 'smtp',
	//'driver' => 'log',
	//'host' => 'smtp.spw.wallonie.be',
	'host' => 'localhost',
	'port' => 1025,
	'from' => array (
		'address' => 'sysadmin@ewbs.be',
		'name' => 'Synapse (local)'
	),
	'encryption' => '',
	'username' => null,
	'password' => null
);