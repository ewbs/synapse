<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpdateNostraFormsTables44 extends Migration {
	
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
		Schema::table( 'nostra_demarche_nostra_form', function (Blueprint $table) {
			$table->unsignedInteger( 'nostra_form_parent_id' )->index()->nullable();
			$table->foreign ( 'nostra_form_parent_id' )->references ( 'id' )->on ( 'nostra_forms' )->onDelete ( 'set null' );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'nostra_demarche_nostra_form', function (Blueprint $table) {
			$table->dropColumn( 'nostra_form_parent_id' );
		});
	}
}
