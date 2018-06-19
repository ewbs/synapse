<?php

use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class RenameDemarchesActionsToEwbsActions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	//TODO le rename column ne passe pas, faudrait aussi voir le nom des contraintes
	public function up() {
		$output = new ConsoleOutput();
		
		
		$output->writeln("Rename table demarchesActions to ewbsActions");
		Schema::rename('demarchesActions', 'ewbsActions');
		DB::statement('ALTER SEQUENCE "demarchesActions_id_seq" RENAME TO "ewbsActions_id_seq";');
		
		
		$output->writeln("Rename table demarchesActionsRevisions to ewbsActionsRevisions");
		DB::statement('ALTER TABLE "demarchesActionsRevisions" DROP CONSTRAINT "demarchesactionsrevisions_demarche_action_id_foreign";');
		DB::statement('ALTER TABLE "demarchesActionsRevisions" RENAME COLUMN "demarche_action_id" TO "ewbs_action_id";');
		DB::statement('ALTER TABLE "demarchesActionsRevisions" ADD CONSTRAINT "ewbsactionsrevisions_ewbs_action_id_foreign" FOREIGN KEY (ewbs_action_id) REFERENCES "ewbsActions" (id) ON DELETE CASCADE;');
		Schema::rename('demarchesActionsRevisions', 'ewbsActionsRevisions');
		DB::statement('ALTER SEQUENCE "demarchesActionsRevisions_id_seq" RENAME TO "ewbsActionsRevisions_id_seq";');
		
		
		$output->writeln("Rename view v_lastRevisionDemarcheAction to v_lastRevisionEwbsAction");
		DB::statement ( 'DROP VIEW v_lastRevisionDemarcheAction' );
		DB::statement ('
			CREATE VIEW v_lastRevisionEwbsAction AS
			SELECT
			r.*
			FROM (
				SELECT ewbs_action_id, MAX(created_at) AS mx
				FROM "ewbsActionsRevisions"
				GROUP BY ewbs_action_id
			) rSub
			JOIN "ewbsActionsRevisions" r ON r.ewbs_action_id = rSub.ewbs_action_id
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
		
		
		$output->writeln("Rename table ewbsActions to demarchesActions");
		Schema::rename('ewbsActions', 'demarchesActions');
		DB::statement('ALTER SEQUENCE "ewbsActions_id_seq" RENAME TO "demarchesActions_id_seq";');
		
		
		$output->writeln("Rename table ewbsActionsRevisions to demarchesActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" DROP CONSTRAINT "ewbsactionsrevisions_ewbs_action_id_foreign";');
		DB::statement('ALTER TABLE "ewbsActionsRevisions" RENAME COLUMN "ewbs_action_id" TO "demarche_action_id";');
		DB::statement('ALTER TABLE "ewbsActionsRevisions" ADD CONSTRAINT "demarchesactionsrevisions_demarche_action_id_foreign" FOREIGN KEY (demarche_action_id) REFERENCES "demarchesActions" (id) ON DELETE CASCADE;');
		Schema::rename('ewbsActionsRevisions', 'demarchesActionsRevisions');
		DB::statement('ALTER SEQUENCE "ewbsActionsRevisions_id_seq" RENAME TO "demarchesActionsRevisions_id_seq";');
		
		
		$output->writeln("Rename view v_lastRevisionEwbsAction to v_lastRevisionDemarcheAction");
		DB::statement ( 'DROP VIEW v_lastRevisionEwbsAction' );
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
}
