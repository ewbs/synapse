<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateUserFilters40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Création des tables de filtres utilisateurs");

		Schema::create ( 'userfilteradministrations', function (Blueprint $table) {
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'administration_id' )->unsigned ()->index ();
			$table->timestamps ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'administration_id' )->references ( 'id' )->on ( 'administrations' );
		});

		Schema::create ( 'userfiltertags', function (Blueprint $table) {
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'taxonomy_tag_id' )->unsigned ()->index ();
			$table->timestamps ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'taxonomy_tag_id' )->references ( 'id' )->on ( 'taxonomytags' );
		});

		Schema::create ( 'userfilterpublics', function (Blueprint $table) {
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->timestamps ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' );
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
		$output->writeln("Suppression des tables de filtres utilisateurs");
		Schema::drop('userfilterpublics');
		Schema::drop('userfilteradministrations');
		Schema::drop('userfiltertags');
	}

}
