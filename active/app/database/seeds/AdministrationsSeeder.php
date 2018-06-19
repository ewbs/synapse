<?php
class AdministrationsSeeder extends Seeder {
	public function run() {
		$regions = array (
				array ( // 1
						'name' => 'FWB',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'name' => 'SPW',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 3
						'name' => 'FWB/SPW',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 4
						'name' => 'OIP',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 5
						'name' => 'Fédéral',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'regions' )->insert ( $regions );
		
		$administrations = array (
				array ( // 1
						'region_id' => 1,
						'name' => 'AGAJSS',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 2
						'region_id' => 1,
						'name' => 'AGI',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 3
						'region_id' => 1,
						'name' => 'AGERS',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 4
						'region_id' => 1,
						'name' => 'AGC',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 5
						'region_id' => 1,
						'name' => 'SG FWB',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 6
						'region_id' => 1,
						'name' => 'AGPE',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 7
						'region_id' => 1,
						'name' => 'CEPIGE',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 8
						'region_id' => 2,
						'name' => 'DGO2',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 9
						'region_id' => 2,
						'name' => 'DTIC',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 10
						'region_id' => 2,
						'name' => 'IWEPS',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 11
						'region_id' => 2,
						'name' => 'SG SPW',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 12
						'region_id' => 2,
						'name' => 'DGT2',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 13
						'region_id' => 2,
						'name' => 'DGO1',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 14
						'region_id' => 2,
						'name' => 'DGO2',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 15
						'region_id' => 2,
						'name' => 'DGO3',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 16
						'region_id' => 2,
						'name' => 'DGO4',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 17
						'region_id' => 2,
						'name' => 'DGO5',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 18
						'region_id' => 2,
						'name' => 'DGO6',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 19
						'region_id' => 2,
						'name' => 'DGO7',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 20
						'region_id' => 3,
						'name' => 'eWBS',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 21
						'region_id' => 4,
						'name' => 'SRWT',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 22
						'region_id' => 4,
						'name' => 'IFAPME',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 23
						'region_id' => 4,
						'name' => 'IFC',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 24
						'region_id' => 4,
						'name' => 'IPW',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 25
						'region_id' => 4,
						'name' => 'ONE',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 26
						'region_id' => 4,
						'name' => 'SWL',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 27
						'region_id' => 4,
						'name' => 'WBI',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 28
						'region_id' => 4,
						'name' => 'FLFNW',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 29
						'region_id' => 4,
						'name' => 'SOWALFIN',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 30
						'region_id' => 4,
						'name' => 'ETNIC',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 31
						'region_id' => 4,
						'name' => 'CSA',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 32
						'region_id' => 4,
						'name' => 'FOREM',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 33
						'region_id' => 4,
						'name' => 'APAQ-W',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 34
						'region_id' => 4,
						'name' => 'ASE',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 35
						'region_id' => 4,
						'name' => 'AST',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 36
						'region_id' => 4,
						'name' => 'AWEX-OFI',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 37
						'region_id' => 4,
						'name' => 'AWIPH',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 38
						'region_id' => 4,
						'name' => 'AWT',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 39
						'region_id' => 4,
						'name' => 'CGT',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 40
						'region_id' => 4,
						'name' => 'CRAC',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 41
						'region_id' => 4,
						'name' => 'SWCS',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 42
						'region_id' => 5,
						'name' => 'SPF Chancellerie du Premier ministre',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 43
						'region_id' => 5,
						'name' => 'SPF Personnel et Organisation',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 44
						'region_id' => 5,
						'name' => 'SPF Budget et Contrôle de la gestion',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 45
						'region_id' => 5,
						'name' => 'SPF Technologie de l\'Information et de la Communication (Fedict)',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 46
						'region_id' => 5,
						'name' => 'SPF Affaires étrangères, Commerce extérieur et Coopération au Développement',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 47
						'region_id' => 5,
						'name' => 'SPF Intérieur',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 48
						'region_id' => 5,
						'name' => 'SPF Finances',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 49
						'region_id' => 5,
						'name' => 'SPF Mobilité et Transports',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 50
						'region_id' => 5,
						'name' => 'SPF Emploi, Travail et Concertation sociale',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 51
						'region_id' => 5,
						'name' => 'SPF Sécurité sociale',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 52
						'region_id' => 5,
						'name' => 'SPF Santé publique, Sécurité de la Chaîne alimentaire et Environnement',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 53
						'region_id' => 5,
						'name' => 'SPF Justice',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 54
						'region_id' => 5,
						'name' => 'SPF Economie, PME, Classes moyennes et Energie',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 55
						'region_id' => 5,
						'name' => 'Ministère de la Défense',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 56
						'region_id' => 5,
						'name' => 'SPP Intégration sociale, Lutte contre la Pauvreté, Economie sociale et Politique des Grandes Villes',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				),
				array ( // 57
						'region_id' => 5,
						'name' => 'SPP Politique scientifique',
						'created_at' => new DateTime (),
						'updated_at' => new DateTime () 
				) 
		);
		
		DB::table ( 'administrations' )->insert ( $administrations );
	}
}
