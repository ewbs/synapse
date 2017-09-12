<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 4.3 vers 4.4.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont ajoutées via la synchro Nostra v2 lors d'un import complet, il s'agit de modifier cette donnée dans les nostraForms lors du déploiement)
 */
class UpgradeTo44 extends Seeder {
	
	public function run() {
		
	}
}