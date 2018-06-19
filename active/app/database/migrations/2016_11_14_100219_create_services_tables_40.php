<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateServicesTables40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Création du catalogue de services");

		Schema::create ( 'ewbsservices', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->text('description');
			$table->timestamps ();
			$table->softDeletes ();
		});

		Schema::create ( 'ewbsservice_taxonomytag', function (Blueprint $table) {
			$table->integer ( 'ewbsservice_id' )->unsigned ()->index ();
			$table->integer ( 'taxonomytag_id' )->unsigned ()->index ();
			$table->foreign ( 'ewbsservice_id' )->references ( 'id' )->on ( 'ewbsservices' )->onDelete ( 'cascade' );
			$table->foreign ( 'taxonomytag_id' )->references ( 'id' )->on ( 'taxonomytags' )->onDelete ( 'cascade' );
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
		$output->writeln("Suppression du catalogue de services");

		Schema::drop('ewbsservice_taxonomytag');
		Schema::drop('ewbsservices');
	}

}
