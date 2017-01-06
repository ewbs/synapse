<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Output\ConsoleOutput;

class ReorganizeDemarchesComponents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output=new ConsoleOutput();
		
		$this->dropDependantViews($output);
		
		foreach(['piece','task'] as $type) {
			$ptype=str_plural($type);
			$uctype=ucfirst($type);
			$puctype=ucfirst($ptype);
			
			$output->writeln("Creation de la table demarche_demarche{$uctype}");
			Schema::create("demarche_demarche{$uctype}", function(Blueprint $table) use($type,$ptype,$uctype,$puctype) {
				$table->increments('id')->unsigned();
				$table->integer('demarche_id')->unsigned()->foreign('demarche_id')->references('id')->on('demarches')->onDelete('cascade');
				$table->integer("{$type}_id")->unsigned()->foreign("{$type}_id")->references('id')->on("demarches{$puctype}")->onDelete('cascade');
				$table->text('name');
				$table->timestamps();
				$table->softDeletes();
				$table->unique(['demarche_id', "{$type}_id", 'name'], "demarche_demarche{$uctype}_unique");
			});
			
			$output->writeln("Creation de la table demarche_demarche{$uctype}_revisions");
			Schema::create("demarche_demarche{$uctype}_revisions", function(Blueprint $table) use($type,$ptype,$uctype,$puctype) {
				$table->increments('id')->unsigned();
				$table->integer("demarche_demarche{$uctype}_id")->unsigned()->foreign("demarche_demarche{$uctype}_id")->references('id')->on("demarche_demarche{$uctype}")->onDelete('cascade');
				$table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->onDelete('set null');
				$table->text('comment')->nullable();
				$table->decimal('cost_administration_currency', 10, 2);
				$table->decimal('cost_citizen_currency', 10, 2);
				$table->integer('volume');
				$table->integer('frequency');
				$table->decimal('gain_potential_administration', 15, 2);
				$table->decimal('gain_potential_citizen', 15, 2);
				$table->decimal('gain_real_administration', 15, 2);
				$table->decimal('gain_real_citizen', 15, 2);
				$table->integer('current_state_id')->unsigned()->nullable()->foreign('current_state_id')->references('id')->on( "demarches{$puctype}States")->onDelete('set null');
				$table->integer('next_state_id')->unsigned()->nullable()->foreign('next_state_id')->references('id')->on( "demarches{$puctype}States")->onDelete('set null');
				$table->timestamps();
				$table->softDeletes();
			});
			
			$output->writeln("Modification de la vue v_lastRevision{$puctype}FromDemarche");
			DB::statement("DROP VIEW v_lastRevision{$puctype}FromDemarche");
			DB::statement ("
				CREATE VIEW v_lastRevision{$puctype}FromDemarche
				AS
				SELECT
					sub.mx,
					dc.demarche_id, dc.{$type}_id, dc.name,
					rev.*
					
				FROM (
					SELECT \"demarche_demarche{$uctype}_id\" AS dc_id, MAX(created_at) AS mx
					FROM \"demarche_demarche{$uctype}_revisions\"
					GROUP BY dc_id
				) sub
				JOIN \"demarche_demarche{$uctype}_revisions\" rev ON (rev.\"demarche_demarche{$uctype}_id\" = sub.dc_id AND rev.created_at = sub.mx)
				JOIN \"demarche_demarche{$uctype}\" dc ON dc.id = rev.\"demarche_demarche{$uctype}_id\""
			);
			//Note : Mettre rev.* en dernier dans le select, car si des nouvelles colonnes sont ajoutées dans le futur, un CREATE OR REPLACE sera alors possible
			
			
			$output->writeln("Modification de la colonne demarche_{$type}_id de la table ewbsActions");
			DB::statement('ALTER TABLE "ewbsActions" RENAME COLUMN "demarche_'.$type.'_id" TO "todelete_demarche_'.$type.'_id";');
			Schema::table('ewbsActions',  function(Blueprint $table) use($type,$uctype) {
				$table->integer("demarche_{$type}_id")->unsigned()->nullable();
				$table->foreign("demarche_{$type}_id")->references('id')->on("demarche_demarche{$uctype}")->onDelete('set null');
			});
		}
		
		$this->createDependantViews($output);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output=new ConsoleOutput();
		
		$this->dropDependantViews($output);
		
		foreach(['piece','task'] as $type) {
			$ptype=str_plural($type);
			$uctype=ucfirst($type);
			$puctype=ucfirst($ptype);
			
			$output->writeln("Restauration de la colonne demarche_{$type}_id de la table ewbsActions");
			Schema::table('ewbsActions', function(Blueprint $table) use($type,$puctype) {
				$table->dropColumn(["demarche_{$type}_id"]);
			});
			DB::statement('ALTER TABLE "ewbsActions" RENAME COLUMN "todelete_demarche_'.$type.'_id" TO "demarche_'.$type.'_id";');
			
			$output->writeln("Restauration de l'ancienne vue v_lastRevision{$puctype}FromDemarche");
			DB::statement("DROP VIEW v_lastRevision{$puctype}FromDemarche");
			DB::statement ("
				CREATE VIEW v_lastRevision{$puctype}FromDemarche
				AS
				SELECT
					sub.mx, dc.*
				FROM (
					SELECT {$type}_id, demarche_id, MAX(created_at) AS mx
					FROM demarche_{$ptype}
					GROUP BY {$type}_id, demarche_id
				) sub
				JOIN demarche_{$ptype} dc ON dc.{$type}_id = sub.{$type}_id
				AND dc.demarche_id = sub.demarche_id
				AND dc.created_at = sub.mx"
			);
			//Note : Mettre cp.* en dernier dans le select, car si des nouvelles colonnes sont ajoutées dans le futur, un CREATE OR REPLACE sera alors possible
			
			$output->writeln("Suppression de la table demarche_demarche{$uctype}_revisions");
			Schema::drop("demarche_demarche{$uctype}_revisions");
			
			$output->writeln("Suppression de la table demarche_demarche{$uctype}");
			Schema::drop("demarche_demarche{$uctype}");
		}
		
		$this->createDependantViews($output);
	}
	
	/**
	 * 
	 * @param ConsoleOutput $output
	 */
	private function dropDependantViews(ConsoleOutput $output){
		$output->writeln("Suppression des vues v_calculatedDemarcheGains et v_calculatedDemarcheGains");
		DB::statement('DROP VIEW v_calculatedDemarcheGains CASCADE');
	}
	
	/**
	 * 
	 * @param ConsoleOutput $output
	 */
	private function createDependantViews(ConsoleOutput $output){
		$output->writeln("Creation de la vue v_calculatedDemarcheGains");
		DB::statement ('
			CREATE VIEW v_calculatedDemarcheGains AS
			SELECT demarche_id, SUM(gpa) AS gain_potential_administration, SUM(gpc) AS gain_potential_citizen, SUM(gra) AS gain_real_administration, SUM(grc) AS gain_real_citizen FROM
			(
				SELECT gain_potential_administration AS gpa, gain_potential_citizen AS gpc, gain_real_administration AS gra, gain_real_citizen AS grc, demarche_id FROM "v_lastrevisionpiecesfromdemarche" WHERE deleted_at IS NULL
				UNION
				select gain_potential_administration AS gpa, gain_potential_citizen AS gpc, gain_real_administration AS gra, gain_real_citizen AS grc, demarche_id FROM "v_lastrevisiontasksfromdemarche" WHERE deleted_at IS NULL
			) AS gains
			GROUP BY demarche_id;
		');
		
		$output->writeln("Creation de la vue v_demarcheGains");
		DB::statement ('
			CREATE VIEW v_demarcheGains AS
			SELECT d.id AS demarche_id,
			COALESCE(r.gain_potential_administration, c.gain_potential_administration, 0::DECIMAL(15,2)) AS gain_potential_administration,
			COALESCE(r.gain_potential_citizen, c.gain_potential_citizen, 0::DECIMAL(15,2)) AS gain_potential_citizen,
			COALESCE(r.gain_real_administration, c.gain_real_administration, 0::DECIMAL(15,2)) AS gain_real_administration,
			COALESCE(r.gain_real_citizen, c.gain_real_citizen, 0::DECIMAL(15,2)) AS gain_real_citizen
			FROM demarches d
			LEFT JOIN v_lastRevisionFromDemarche r ON d.id=r.demarche_id
			LEFT JOIN v_calculatedDemarcheGains c ON d.id=c.demarche_id;
		');
	}
}
