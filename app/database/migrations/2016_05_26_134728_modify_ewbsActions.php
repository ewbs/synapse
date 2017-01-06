<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModifyEwbsActions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		$output->writeln("Modify table ewbsActions for ideas and demarches");
		
		
		DB::statement('ALTER TABLE "ewbsActions" ALTER COLUMN "demarche_id" DROP NOT NULL;');
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->integer ( 'idea_id' )->unsigned ()->index()->nullable();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'set null' );
			
			$table->text('token')->nullable();
		});
		
		
		$output->writeln("Modify table ewbsActionsRevisions for user_id nullable");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" ALTER COLUMN "user_id" DROP NOT NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		$output->writeln("Modify table ewbsActions for ideas and demarches");
		
		
		DB::statement('ALTER TABLE "ewbsActions" ALTER COLUMN "demarche_id" SET NOT NULL;');
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->dropColumn('idea_id');
			$table->dropColumn('token');
		});
		
		
		$output->writeln("Modify table ewbsActionsRevisions for user_id nullable");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" ALTER COLUMN "user_id" SET NOT NULL;');
	}
}
