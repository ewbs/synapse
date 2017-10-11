<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateVFirstRevisionEwbsAction43 extends Migration {
	
	/**
	 *
	 * @var ConsoleOutput
	 */
	private $output;
	
	public function __construct() {
		$this->output = new ConsoleOutput();
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$this->output->writeln("Creation de la vue v_firstRevisionEwbsAction");
		DB::statement ('
			CREATE OR REPLACE VIEW v_firstRevisionEwbsAction AS
			SELECT
			r.*
			FROM (
				SELECT ewbs_action_id, MIN(id) AS id
				FROM "ewbsActionsRevisions"
				GROUP BY ewbs_action_id
			) rSub
			JOIN "ewbsActionsRevisions" r ON rSub.id = r.id;
		');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$this->output->writeln("Suppression de la vue v_firstRevisionEwbsAction");
		DB::statement ('DROP VIEW v_firstRevisionEwbsAction');
	}

}
