<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateDemarchesActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		$output->writeln("Création de la table demarchesActions");
		Schema::create ( 'demarchesActions', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->integer ( 'demarche_id' )->unsigned ()->index();
			$table->integer ( 'demarche_piece_id' )->unsigned ()->nullable ();
			$table->integer ( 'demarche_task_id' )->unsigned ()->nullable ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'demarche_piece_id' )->references ( 'id' )->on ( 'demarchesPieces' )->onDelete ( 'set null' );
			$table->foreign ( 'demarche_task_id' )->references ( 'id' )->on ( 'demarchesTasks' )->onDelete ( 'set null' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		$output->writeln("Création de la table demarchesActionsRevisions");
		Schema::create ( 'demarchesActionsRevisions', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->integer ( 'demarche_action_id' )->unsigned ();
			$table->enum('state', ['todo', 'progress', 'done', 'givenup']);
			$table->text('description')->nullable ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'set null' );
			$table->foreign ( 'demarche_action_id' )->references ( 'id' )->on ( 'demarchesActions' )->onDelete ( 'cascade' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		$output->writeln("Création de la vue v_lastRevisionDemarcheAction");
		DB::statement ('
			CREATE VIEW v_lastRevisionDemarcheAction AS
			SELECT
			r.*
			FROM (
				SELECT demarche_action_id, MAX(created_at) AS mx
				FROM "demarchesActionsRevisions"
				GROUP BY demarche_action_id
			) rSub
			JOIN "demarchesActionsRevisions" r ON r.demarche_action_id = rSub.demarche_action_id
			AND rSub.mx = r.created_at;
		');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		
		$output->writeln("Suppression de la vue v_lastRevisionDemarcheAction");
		DB::statement ( 'DROP VIEW v_lastRevisionDemarcheAction' );
		
		$output->writeln("Suppression de la table demarchesActionsRevisions");
		Schema::drop ( 'demarchesActionsRevisions' );
		
		$output->writeln("Suppression de la table demarchesActions");
		Schema::drop ( 'demarchesActions' );
	}

}
