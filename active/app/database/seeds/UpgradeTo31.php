<?php

/*
 * Ce script ne doit être appelé que lors d'un upgrade de 3.0 vers 3.1.
 * Ne l'appelez surtout pas pour monter une DB de test (les valeurs sont déjà ajoutées via PermissionsRolesSeeder.php)
 */
class UpgradeTo31 extends Seeder {
	
	public function run() {
		DB::beginTransaction();
		try {
			foreach(['piece','task'] as $type) $this->process($type);
			DB::commit();
			$this->command->info("Copie dans les nouvelles table OK");
		}
		catch(Exception $e) {
			DB::rollBack();
			$this->command->error('Erreur durant l\'execution, rollback sur la transaction : '.$e->getMessage());
			Log::error($e);
		}
	}
	
	private function process($type) {
		$ptype=str_plural($type);
		$uctype=ucfirst($type);
		$puctype=ucfirst($ptype);
		$this->command->info("Copie des demarche_{$ptype} dans les nouvelles table");
		
		// Regrouper les composants de démarche par id de démarche et de composant
		$items=[];
		foreach(DB::table('demarche_'.$ptype)->orderBy('created_at')->get() as $row) {
			$component_column=$type.'_id';
			$component_id=$row->$component_column;
			
			// Garder le résultat complet dans un attribut data
			$items[$row->demarche_id][$component_id]['data'][]=$row;
			
			// Prendre en + le premier created_at
			if(!isset($items[$row->demarche_id][$component_id]['created_at']))
				$items[$row->demarche_id][$component_id]['created_at']=$row->created_at;
			
			// Et prendre systématiquement le updated_at et deleted_at (ainsi le dernier sera considéré)
			$items[$row->demarche_id][$component_id]['updated_at']=$row->updated_at;
			$items[$row->demarche_id][$component_id]['deleted_at']=$row->deleted_at;
		}
		
		// Créer les nouveaux composants de démarche + leurs révisions
		foreach($items as $demarche_id=>$components) {
			foreach($components as $component_id=>$component) {
				$this->command->info("Insertion composant pour demarche{$demarche_id} & {$type}{$component_id}, + ".count($component['data'])." revisions");
				$insertedId=DB::table('demarche_demarche'.$uctype)->insertGetId([
					'demarche_id'=>$demarche_id,
					"{$type}_id"=>$component_id,
					'name'=>DB::table('demarches'.$puctype)->find($component_id)->name,
					'created_at'=>$component['created_at'],
					'updated_at'=>$component['updated_at'],
					'deleted_at'=>$component['deleted_at'],
				]);
				foreach($component['data'] as $row) {
					DB::table('demarche_demarche'.$uctype.'_revisions')->insert([
					"demarche_demarche{$uctype}_id"=>$insertedId,
					'user_id'=>$row->user_id,
					'comment'=>$row->comment,
					'cost_administration_currency'=>$row->cost_administration_currency,
					'cost_citizen_currency'=>$row->cost_citizen_currency,
					'volume'=>$row->volume,
					'frequency'=>$row->frequency,
					'gain_potential_administration'=>$row->gain_potential_administration,
					'gain_potential_citizen'=>$row->gain_potential_citizen,
					'gain_real_administration'=>$row->gain_real_administration,
					'gain_real_citizen'=>$row->gain_real_citizen,
					'created_at'=>$row->created_at,
					'updated_at'=>$row->updated_at,
					'deleted_at'=>$row->deleted_at,
					]);
				}
			}
		}
		
		$this->command->info("ewbsActions : Reconstruction des references demarche_{$type}_id vers le demarcheComponent plutot que le Component");
		$newcol="demarche_{$type}_id";
		$oldcol="todelete_{$newcol}";
		
		foreach(EwbsAction::withTrashed()->whereNotNull($oldcol)->get() as $action) {
			/* @var EwbsAction $action */
			$componentId=$action->getAttribute($oldcol);
			$demarcheComponent=$action->demarche()->getQuery()->withTrashed()
			->join("demarche_demarche{$uctype}", "demarche_demarche{$uctype}.demarche_id", '=', 'demarches.id')
			->where("demarche_demarche{$uctype}.{$type}_id", '=',$componentId)->first();
			if($demarcheComponent) { // Vu que le lien était fait vers le catalogue, rien ne remettait à null le lien dans l'action => il est dc possible qu'on ne trouve pas de correspondance dans les demarcheComponent
				// Note : Pas via la couche object, car on ne veut pas du trigger créant une nouvelle révision après la sauvegarde de l'action
				DB::statement('UPDATE "ewbsActions" SET '.$newcol.'=? WHERE id=?',[$demarcheComponent->id, $action->id]);
			}
		}
	}
}