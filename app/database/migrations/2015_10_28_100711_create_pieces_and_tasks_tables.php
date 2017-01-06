<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreatePiecesAndTasksTables extends Migration {
	
	
	/**
	 * PIECES ET TACHES LIEES AUX DEMARCHES
	 * (pièces probantes et tâches pour le SCM)
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		/*
		 * NATURES DES PIECES ET TACHES
		 */
		$output->writeln("Création de la table demarchesPiecesAndTasksTypes");
		Schema::create ( 'demarchesPiecesAndTasksTypes', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->enum ( 'for', array (
				'task',
				'piece',
				'all' 
			));
			$table->string ( 'name', 1024 );
			$table->text ( 'description' )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		/*
		 * TARIFS POUR LES TACHES
		 */
		/*$output->writeln("Création de la table demarchesTasksRates");
		Schema::create ( 'demarchesTasksRates', function (Blueprint $table) {
			$table->increments ( 'id' );
			$table->string ( 'name', 1024 );
			$table->decimal ( 'hour_rate', 10, 2 );
			$table->text ( 'description' )->nullable ();
			$table->enum ( 'who', array (
				'citizen',
				'administration' 
			));
			$table->timestamps ();
			$table->softDeletes ();
		});*/
		
		/*
		 * PIECES BROBANTES : PIECES
		 */
		$output->writeln("Création de la table demarchesPieces");
		Schema::create ( 'demarchesPieces', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'name', 1024 );
			$table->text ( 'description' )->nullable ();
			$table->decimal ( 'cost_administration_currency', 10, 2 )->nullable ();
			$table->decimal ( 'cost_citizen_currency', 10, 2 )->nullable ();
			$table->integer ( 'type_id' )->unsigned ()->nullable ();
			$table->foreign ( 'type_id' )->references ( 'id' )->on ( 'demarchesPiecesAndTasksTypes' )->onDelete ( 'set null' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		/*
		 * PIECES BROBANTES : TACHES
		 */
		$output->writeln("Création de la table demarchesTasks");
		Schema::create ( 'demarchesTasks', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'name', 1024 );
			$table->text ( 'description' )->nullable ();
			/*$table->decimal ( 'cost_administration_time', 8, 4 );
			$table->decimal ( 'cost_citizen_time', 8, 4 );*/
			$table->decimal ( 'cost_administration_currency', 10, 2 )->nullable ();
			$table->decimal ( 'cost_citizen_currency', 10, 2 )->nullable ();
			$table->integer ( 'type_id' )->unsigned ()->nullable ();
			/*$table->integer ( 'rate_administration_id' )->unsigned ();
			$table->integer ( 'rate_citizen_id' )->unsigned ();*/
			$table->foreign ( 'type_id' )->references ( 'id' )->on ( 'demarchesPiecesAndTasksTypes' )->onDelete ( 'set null' );
			/*$table->foreign ( 'rate_administration_id' )->references ( 'id' )->on ( 'demarchesTasksRates' )->onDelete ( 'set null' );
			$table->foreign ( 'rate_citizen_id' )->references ( 'id' )->on ( 'demarchesTasksRates' )->onDelete ( 'set null' );*/
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		/*
		 * Pieces liées aux démarches
		 * Table de jointure
		 * Les FK ne volontairement pas des index, car on peut avoir des doublons (historique des modifications d'une même pièce
		 * on utilise donc une PK distincte pour gérer ca
		 */
		$output->writeln("Création de la table demarche_pieces");
		Schema::create ( 'demarche_pieces', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'piece_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->decimal ( 'cost_administration_currency', 10, 2 );
			$table->decimal ( 'cost_citizen_currency', 10, 2 );
			$table->integer ( 'volume' );
			$table->integer ( 'frequency' );
			$table->decimal ( 'gain_potential_administration', 15, 2 );
			$table->decimal ( 'gain_potential_citizen', 15, 2 );
			$table->decimal ( 'gain_real_administration', 15, 2 );
			$table->decimal ( 'gain_real_citizen', 15, 2 );
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'piece_id' )->references ( 'id' )->on ( 'demarchesPieces' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		/*
		 * Tâches liées aux démarches
		 * Table de jointure
		 * Les FK ne volontairement pas des index, car on peut avoir des doublons (historique des modifications d'une même pièce
		 * on utilise donc une PK distincte pour gérer ca
		 */
		$output->writeln("Création de la table demarche_tasks");
		Schema::create ( 'demarche_tasks', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'task_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->decimal ( 'cost_administration_currency', 10, 2 );
			$table->decimal ( 'cost_citizen_currency', 10, 2 );
			$table->integer ( 'volume' );
			$table->integer ( 'frequency' );
			$table->decimal ( 'gain_potential_administration', 15, 2 );
			$table->decimal ( 'gain_potential_citizen', 15, 2 );
			$table->decimal ( 'gain_real_administration', 15, 2 );
			$table->decimal ( 'gain_real_citizen', 15, 2 );
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'task_id' )->references ( 'id' )->on ( 'demarchesTasks' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		
		/*
		 * Fichiers SCM Lights uploadés et liés à une démarche.
		 */
		$output->writeln("Création de la table demarche_scms");
		Schema::create ( 'demarche_scms', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'filename' );
			$table->integer ( 'processed' )->nullable(); // pour éventuellement différer le traitement avec des crons.
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->timestamps ();
		});
		
		/*
		 * Vue pour obtenir les pièces liée à une démarche dans leur dernière révision
		 * (donc sans l'historique des modifications)
		 */
		$output->writeln("Création de la vue v_lastRevisionPiecesFromDemarche");
		DB::statement ('
			CREATE VIEW v_lastRevisionPiecesFromDemarche
			AS
			SELECT 
				dp1.*,
				dp2.mx
			FROM (
				SELECT piece_id, demarche_id, MAX(created_at) AS mx
				FROM demarche_pieces
				GROUP BY piece_id, demarche_id
			) dp2 
			JOIN demarche_pieces dp1 ON dp1.piece_id = dp2.piece_id 
			AND dp2.mx = dp1.created_at 
			AND dp1.demarche_id = dp2.demarche_id;'
		);
		
		/*
		 * Vue pour obtenir les tâches liée à une démarche dans leur dernière révision
		 * (donc sans l'historique des modifications)
		 */
		$output->writeln("Création de la vue v_lastRevisionTasksFromDemarche");
		DB::statement ('
			CREATE VIEW v_lastRevisionTasksFromDemarche
			AS
			SELECT
				dp1.*,
				dp2.mx
			FROM (
				SELECT task_id, demarche_id, MAX(created_at) AS mx
				FROM demarche_tasks
				GROUP BY task_id, demarche_id
			) dp2
			JOIN demarche_tasks dp1 ON dp1.task_id = dp2.task_id
			AND dp2.mx = dp1.created_at
			AND dp1.demarche_id = dp2.demarche_id;'
		);
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		
		$output->writeln("Suppression de la table demarche_scms");
		Schema::drop ( 'demarche_scms' );
		
		$output->writeln("Suppression de la vue v_lastRevisionTasksFromDemarche");
		DB::statement ( 'DROP VIEW v_lastRevisionTasksFromDemarche' );
		
		$output->writeln("Suppression de la vue v_lastRevisionPiecesFromDemarche");
		DB::statement ( 'DROP VIEW v_lastRevisionPiecesFromDemarche' );
		
		$output->writeln("Suppression de la table demarche_tasks");
		Schema::drop ( 'demarche_tasks' );
		
		$output->writeln("Suppression de la table demarche_pieces");
		Schema::drop ( 'demarche_pieces' );
		
		$output->writeln("Suppression de la table demarchesTasks");
		Schema::drop ( 'demarchesTasks' );
		
		$output->writeln("Suppression de la table demarchesPieces");
		Schema::drop ( 'demarchesPieces' );
		
		//$output->writeln("Suppression de la table demarchesTasksRates");
		//Schema::drop ( 'demarchesTasksRates' );
		
		$output->writeln("Suppression de la table demarchesPiecesAndTasksTypes");
		Schema::drop ( 'demarchesPiecesAndTasksTypes' );
	}
}
