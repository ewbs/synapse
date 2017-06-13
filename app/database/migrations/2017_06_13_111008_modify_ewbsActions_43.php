<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModifyEwbsActions43 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		$output->writeln("Ajout de la valeur standby dans l'enumeration de la colonne state de la table ewbsActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" DROP CONSTRAINT "demarchesActionsRevisions_state_check"');
		$types = ['todo', 'progress', 'done', 'standby', 'givenup'];
		$result = join( ', ', array_map(function( $value ){ return sprintf("'%s'::character varying", $value); }, $types) );
		DB::statement("ALTER TABLE \"ewbsActionsRevisions\" add CONSTRAINT \"demarchesActionsRevisions_state_check\" CHECK (state::text = ANY (ARRAY[$result]::text[]))");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		
		$output->writeln("Retrait de la valeur standby dans l'enumeration de la colonne state de la table ewbsActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" DROP CONSTRAINT "demarchesActionsRevisions_state_check"');
		$types = ['todo', 'progress', 'done', 'givenup'];
		$result = join( ', ', array_map(function( $value ){ return sprintf("'%s'::character varying", $value); }, $types) );
		DB::statement("ALTER TABLE \"ewbsActionsRevisions\" add CONSTRAINT \"demarchesActionsRevisions_state_check\" CHECK (state::text = ANY (ARRAY[$result]::text[]))");
	}

}
