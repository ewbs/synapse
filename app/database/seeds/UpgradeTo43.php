<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 4.2 vers 4.3.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PolesExpertisesSeeder.php)
 */
class UpgradeTo43 extends Seeder {
	
	public function run() {
		$this->call('ExpertisesPolesSeeder');
	}
}