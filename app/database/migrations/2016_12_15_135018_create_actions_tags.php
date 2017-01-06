<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateActionsTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Création de la jointure entre tags et actions");

		Schema::create ( 'ewbs_action_taxonomy_tag', function (Blueprint $table) {
			$table->integer ( 'ewbs_action_id' )->unsigned ()->index ();
			$table->integer ( 'taxonomy_tag_id' )->unsigned ()->index ();
			$table->foreign ( 'ewbs_action_id' )->references ( 'id' )->on ( 'ewbsActions' )->onDelete ( 'cascade' );
			$table->foreign ( 'taxonomy_tag_id' )->references ( 'id' )->on ( 'taxonomytags' )->onDelete ( 'cascade' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$output=new ConsoleOutput();
		$output->writeln("Suppression de la jointure entre tags et actions");
		Schema::drop('ewbs_action_taxonomy_tag');
	}

}
