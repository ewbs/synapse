<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreatePiecesAndTasksStatesTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		$this->dropViews();
		
		$output->writeln("Création de la table demarchesPiecesStates");
		Schema::create ( 'demarchesPiecesStates', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('code');
			$table->text('name');
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		$output->writeln("Création de la table demarchesTasksStates");
		Schema::create ( 'demarchesTasksStates', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('code');
			$table->text('name');
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		$output->writeln("Création sur la table demarche_pieces des colonnes liées à la table demarchesPiecesStates");
		Schema::table('demarche_pieces', function (Blueprint $table) {
			$table->integer ( 'current_state_id' )->unsigned ()->nullable ();
			$table->integer ( 'next_state_id' )->unsigned ()->nullable ();
			$table->foreign ( 'current_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
			$table->foreign ( 'next_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
		});
		
		$output->writeln("Création sur la table demarche_tasks des colonnes liées à la table demarchesTasksStates");
		Schema::table('demarche_tasks', function (Blueprint $table) {
			$table->integer ( 'current_state_id' )->unsigned ()->nullable ();
			$table->integer ( 'next_state_id' )->unsigned ()->nullable ();
			$table->foreign ( 'current_state_id' )->references ( 'id' )->on ( 'demarchesTasksStates' )->onDelete ( 'set null' );
			$table->foreign ( 'next_state_id' )->references ( 'id' )->on ( 'demarchesTasksStates' )->onDelete ( 'set null' );
		});
		
		$this->createViews();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		
		$this->dropViews();
		
		$output->writeln("Suppression sur la table demarche_tasks des colonnes liées à la table demarchesTasksStates");
		Schema::table('demarche_tasks', function (Blueprint $table) {
			$table->dropColumn('current_state_id');
			$table->dropColumn('next_state_id');
		});
		
		$output->writeln("Suppression sur la table demarche_pieces des colonnes liées à la table demarchesPiecesStates");
		Schema::table('demarche_pieces', function (Blueprint $table) {
			$table->dropColumn('current_state_id');
			$table->dropColumn('next_state_id');
		});
		
		$output->writeln("Suppression de la table demarchesTasksStates");
		Schema::drop ( 'demarchesTasksStates' );
		
		$output->writeln("Suppression de la table demarchesPiecesStates");
		Schema::drop ( 'demarchesPiecesStates' );
		
		$this->createViews();
	}
	
	/**
	 * Re-création des vues impactées par l'ajout des colonnes dans les démarches-pièces et démarches-tâches
	 * 
	 * La suppression était nécessaire (et du coup la suppression des vues liées), cf. la note ci-dessous
	 */
	private function createViews() {
		$output = new ConsoleOutput();
		
		$output->writeln("Création de la vue v_lastRevisionPiecesFromDemarche");
		//Note : Mettre dp1.* en dernier dans le select, car si des nouvelles colonnes sont ajoutées dans le futur, un CREATE OR REPLACE sera alors possible
		DB::statement ('
			CREATE VIEW v_lastRevisionPiecesFromDemarche
			AS
			SELECT 
				dp2.mx,
				dp1.*
			FROM (
				SELECT piece_id, demarche_id, MAX(created_at) AS mx
				FROM demarche_pieces
				GROUP BY piece_id, demarche_id
			) dp2 
			JOIN demarche_pieces dp1 ON dp1.piece_id = dp2.piece_id 
			AND dp2.mx = dp1.created_at 
			AND dp1.demarche_id = dp2.demarche_id;'
		);
		
		$output->writeln("Création de la vue v_lastRevisionTasksFromDemarche");
		//Note : Mettre dp1.* en dernier dans le select, car si des nouvelles colonnes sont ajoutées dans le futur, un CREATE OR REPLACE sera alors possible
		DB::statement ('
			CREATE VIEW v_lastRevisionTasksFromDemarche
			AS
			SELECT
				dp2.mx,
				dp1.*
			FROM (
				SELECT task_id, demarche_id, MAX(created_at) AS mx
				FROM demarche_tasks
				GROUP BY task_id, demarche_id
			) dp2
			JOIN demarche_tasks dp1 ON dp1.task_id = dp2.task_id
			AND dp2.mx = dp1.created_at
			AND dp1.demarche_id = dp2.demarche_id;'
		);
		
		$output->writeln("Création de la vue v_calculatedDemarcheGains");
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
		
		$output->writeln("Création de la vue v_demarcheGains");
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
	
	/**
	 * Suppression des vues impactées par l'ajout des colonnes dans les démarches-pièces et démarches-tâches
	 * 
	 * La suppression était nécessaire, cf. la méthode createViews()
	 */
	private function dropViews() {
		$output = new ConsoleOutput();
		
		$output->writeln("Suppression de la vue v_demarcheGains");
		DB::statement ( 'DROP VIEW v_demarcheGains' );
		
		$output->writeln("Suppression de la vue v_calculatedDemarcheGains");
		DB::statement ( 'DROP VIEW v_calculatedDemarcheGains' );
		
		$output->writeln("Suppression de la vue v_lastRevisionPiecesFromDemarche");
		DB::statement ( 'DROP VIEW v_lastRevisionPiecesFromDemarche' );
		
		$output->writeln("Suppression de la vue v_lastRevisionTasksFromDemarche");
		DB::statement ( 'DROP VIEW v_lastRevisionTasksFromDemarche' );
	}
}
