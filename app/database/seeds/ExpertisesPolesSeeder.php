<?php
class ExpertisesPolesSeeder extends Seeder {
	
	public function run() {
		
		$poles=[
			['id'=>'1', 'order'=>'1', 'name'=>'Conseil'],
			['id'=>'2', 'order'=>'2', 'name'=>'Production & Gestion'],
			['id'=>'3', 'order'=>'3', 'name'=>'BCED'],
		];
		$poles=$this->addTimestamps($poles);
		
		$expertises=[
			['id'=>'1',  'order'=>'1',  'pole_id'=>'1', 'name'=>'Analyse approche intégrée'],
			['id'=>'2',  'order'=>'2',  'pole_id'=>'1', 'name'=>'Inventaire des pièces et tâches'],
			['id'=>'3',  'order'=>'3',  'pole_id'=>'1', 'name'=>'Parcours usager'],
			['id'=>'4',  'order'=>'4',  'pole_id'=>'1', 'name'=>'Lisibilité des documents'],
			['id'=>'5',  'order'=>'5',  'pole_id'=>'1', 'name'=>'Analyse Principe de confiance'],
			['id'=>'6',  'order'=>'6',  'pole_id'=>'2', 'name'=>'Lisibilité des formulaires'],
			['id'=>'7',  'order'=>'7',  'pole_id'=>'2', 'name'=>'Dématérialisation de formulaire'],
			['id'=>'8',  'order'=>'8',  'pole_id'=>'3', 'name'=>'Accès à une source authentique'],
			['id'=>'9',  'order'=>'9',  'pole_id'=>'2', 'name'=>'Intégration à "mon espace"'],
			['id'=>'10', 'order'=>'10', 'pole_id'=>'3', 'name'=>'Accès à BCED-Wi'],
			['id'=>'11', 'order'=>'11', 'pole_id'=>'3', 'name'=>'Accompagnement à la création de source authentique'],
			['id'=>'12', 'order'=>'12', 'pole_id'=>'3', 'name'=>'Sensibilisation à la sécurité des données'],
			['id'=>'13', 'order'=>'13', 'pole_id'=>'3', 'name'=>'Sensibilisation à la protection de la vie privée'],
			['id'=>'14', 'order'=>'14', 'pole_id'=>'2', 'name'=>'Ajout de la démarche dans Nostra'],
			['id'=>'15', 'order'=>'15', 'pole_id'=>'3', 'name'=>'Autre'],
		];
		$expertises=$this->addTimestamps($expertises);
		
		DB::beginTransaction();
		try {
			DB::table('poles')->insert($poles);
			DB::table('expertises')->insert($expertises);
			DB::commit();
		}
		catch(Exception $e) {
			DB::rollBack();
			$this->command->error('Erreur durant l\'exécution, rollback sur la transaction : '.$e->getMessage());
			Log::error($e);
		}
	}
	
	private function addTimestamps($data){
		foreach($data as $i=>$item){
			$data[$i]['created_at']=new DateTime;
			$data[$i]['updated_at']=new DateTime;
		}
		return $data;
	}
}
