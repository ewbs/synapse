<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class SubactionsEwbsActions40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		$output->writeln("Table ewbsActions : add parent");
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->boolean( 'sub' )->default(true);
			//$table->text( 'description' )->nullable();
			$table->integer ( 'parent_id' )->unsigned ()->index()->nullable();
			$table->foreign ( 'parent_id' )->references ( 'id' )->on ( 'ewbsActions' )->onDelete ( 'cascade' );
		});
		
		$output->writeln("Table ewbsActionsRevisions : add priority");
		Schema::table ( 'ewbsActionsRevisions', function (Blueprint $table) {
			$table->enum('priority', ['low', 'normal', 'high', 'critical'])->default('normal');
		});
		//DB::statement('ALTER TABLE "ewbsActionsRevisions" RENAME COLUMN "description" TO "comment";'); // la méthode renameColumn ne fct pas...
		
		$output->writeln("View v_lastRevisionEwbsAction : Recreate");
		DB::statement ('DROP VIEW v_lastRevisionEwbsAction');
		DB::statement ('
			CREATE OR REPLACE VIEW v_lastRevisionEwbsAction AS
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
		$output->writeln("Table ewbsActions : remove parent");
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->dropColumn( 'parent_id' );
			$table->dropColumn( 'sub' );
			//$table->dropColumn( 'description' );
		});
		
		$output->writeln("View v_lastRevisionEwbsAction : Drop");
		DB::statement ('DROP VIEW v_lastRevisionEwbsAction');
		
		$output->writeln("Table ewbsActionsRevisions : remove priority");
		Schema::table ( 'ewbsActionsRevisions', function (Blueprint $table) {
			$table->dropColumn( 'priority' );
		});
		//DB::statement('ALTER TABLE "ewbsActionsRevisions" RENAME COLUMN "comment" TO "description";'); // la méthode renameColumn ne fct pas...
		
		$output->writeln("View v_lastRevisionEwbsAction : Recreate");
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

}
