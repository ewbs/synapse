<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateTaxonomydemarchesTable40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Création de la jointure entre tags et demarches");

		Schema::create ( 'demarche_taxonomy_tag', function (Blueprint $table) {
			$table->integer ( 'demarche_id' )->unsigned ()->index ();
			$table->integer ( 'taxonomy_tag_id' )->unsigned ()->index ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
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
		$output->writeln("Suppression de la jointure entre tags et demarches");
		Schema::drop('demarche_taxonomy_tag');
	}

}
