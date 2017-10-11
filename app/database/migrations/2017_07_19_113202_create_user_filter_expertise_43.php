<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateUserFilterExpertise43 extends Migration {
	
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
		$this->output->writeln("Creation de la table de filtre utilisateur par expertise");
		
		Schema::create ( 'userfilterexpertises', function (Blueprint $table) {
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'expertise_id' )->unsigned ()->index ();
			$table->timestamps ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'expertise_id' )->references ( 'id' )->on ( 'expertises' );
			$table->primary(['user_id', 'expertise_id']);
		});
		
		// Note : Bugfix afin que les tables de liaison aient bien une clé primaire
		$this->output->writeln("Ajout d'une cle primaire sur les autres filtres utilisateur");
		Schema::table('userfilteradministrations', function (Blueprint $table) {
			$table->primary(['user_id', 'administration_id']);
		});
		Schema::table('userfiltertags', function (Blueprint $table) {
			$table->primary(['user_id', 'taxonomy_tag_id']);
		});
		Schema::table('userfilterpublics', function (Blueprint $table) {
			$table->primary(['user_id', 'nostra_public_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$this->output->writeln("Suppression de la table de filtre par expertise");
		Schema::drop('userfilterexpertises');
		
		$this->output->writeln("Suppression de la primaire sur les autres filtres utilisateur");
		Schema::table('userfilteradministrations', function (Blueprint $table) {
			$table->dropPrimary();
		});
		Schema::table('userfiltertags', function (Blueprint $table) {
			$table->dropPrimary();
		});
		Schema::table('userfilterpublics', function (Blueprint $table) {
			$table->dropPrimary();
		});
	}
}
