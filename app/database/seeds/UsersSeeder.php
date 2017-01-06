<?php
class UsersSeeder extends Seeder {
	public function run() {
		$users = array (
				array ( // 1
						'username' => 'julian',
						'email' => 'julian.davreux@ensemblesimplifions.be',
						'password' => Hash::make ( 'julian' ),
						'confirmed' => 1,
						'confirmation_code' => md5 ( microtime () . Config::get ( 'app.key' ) ),
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'username' => 'elodie',
						'email' => 'elodie.delvaux@ensemblesimplifions.be',
						'password' => Hash::make ( 'elodie' ),
						'confirmed' => 1,
						'confirmation_code' => md5 ( microtime () . Config::get ( 'app.key' ) ),
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 3
						'username' => 'bernard',
						'email' => 'bernard.dubuisson@ensemblesimplifions.be',
						'password' => Hash::make ( 'bernard' ),
						'confirmed' => 1,
						'confirmation_code' => md5 ( microtime () . Config::get ( 'app.key' ) ),
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 4
						'username' => 'mgrenson',
						'email' => 'mgrenson@defimedia.be',
						'password' => Hash::make ( 'mgrenson' ),
						'confirmed' => 1,
						'confirmation_code' => md5 ( microtime () . Config::get ( 'app.key' ) ),
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		DB::table ( 'users' )->insert ( $users );
	}
}
