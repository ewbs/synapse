<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class UpdateFormsTables44 extends Migration {
	
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
		//
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		//
	}
}
