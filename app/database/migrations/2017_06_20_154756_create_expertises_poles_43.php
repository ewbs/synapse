<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateExpertisesPoles43 extends Migration {

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
		$this->output->writeln("Creation des tables poles & expertises ");
		
		Schema::create ( 'poles', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->integer('order');
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		Schema::create ( 'expertises', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->text('name');
			$table->integer('order');
			$table->integer ( 'pole_id' )->unsigned ()->index ()->nullable()->foreign ( 'pole_id' )->references ( 'id' )->on ( 'poles' )->onDelete ( 'set null' );
			$table->timestamps ();
			$table->softDeletes ();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$this->output->writeln("Suppression des tables poles & expertises");
		Schema::drop('expertises');
		Schema::drop('poles');
	}
}