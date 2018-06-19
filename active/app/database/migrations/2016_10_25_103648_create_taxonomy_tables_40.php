<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateTaxonomyTables40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Création des tables de taxonomie");

		Schema::create ( 'taxonomycategories', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->timestamps ();
			$table->softDeletes ();
		});

		Schema::create ( 'taxonomytags', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->integer ( 'taxonomy_category_id' )->unsigned ()->index ();
			$table->integer ( 'group' )->unsigned()->nullable();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'taxonomy_category_id' )->references ( 'id' )->on ( 'taxonomycategories' )->onDelete ( 'cascade' );
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
		$output->writeln("Suppression des tables de taxonomie");
		Schema::drop('taxonomytags');
		Schema::drop('taxonomycategories');
	}

}
