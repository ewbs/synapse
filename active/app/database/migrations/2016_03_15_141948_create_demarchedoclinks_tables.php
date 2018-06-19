<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateDemarchedoclinksTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output = new ConsoleOutput();
		
		$output->writeln("Création de la table demarchesDocLinks");
		Schema::create ( 'demarchesDocLinks', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('demarche_id')->unsigned();
			$table->text('name');
			$table->text('description');
			$table->text('url');
			$table->timestamps();
			$table->softDeletes();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$output = new ConsoleOutput();
		$output->writeln("Suppression sur la table demarcheDocLinks");
		Schema::drop ( 'demarchesDocLinks' );
		
	}

}
