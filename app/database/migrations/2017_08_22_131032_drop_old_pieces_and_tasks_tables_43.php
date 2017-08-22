<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Ces tables ne sont plus utiles depuis la migration retransférant lee contenu dans des tables séparant les données de base de leurs révisions
 * 
 * @author mgrenson
 * @see ReorganizeDemarchesComponents
 */

class DropOldPiecesAndTasksTables43 extends Migration {

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
		$this->output->writeln("Suppression de la table demarche_pieces");
		Schema::drop('demarche_pieces');
		$this->output->writeln("Suppression de la table demarche_tasks");
		Schema::drop('demarche_tasks');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		/*
		 * Pieces liées aux démarches
		 * Table de jointure
		 * Les FK ne volontairement pas des index, car on peut avoir des doublons (historique des modifications d'une même pièce
		 * on utilise donc une PK distincte pour gérer ca
		 */
		$this->output->writeln("Création de la table demarche_pieces");
		Schema::create ( 'demarche_pieces', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'piece_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->decimal ( 'cost_administration_currency', 10, 2 );
			$table->decimal ( 'cost_citizen_currency', 10, 2 );
			$table->integer ( 'volume' );
			$table->integer ( 'frequency' );
			$table->decimal ( 'gain_potential_administration', 15, 2 );
			$table->decimal ( 'gain_potential_citizen', 15, 2 );
			$table->decimal ( 'gain_real_administration', 15, 2 );
			$table->decimal ( 'gain_real_citizen', 15, 2 );
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'piece_id' )->references ( 'id' )->on ( 'demarchesPieces' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		/*
		 * Tâches liées aux démarches
		 * Table de jointure
		 * Les FK ne volontairement pas des index, car on peut avoir des doublons (historique des modifications d'une même pièce
		 * on utilise donc une PK distincte pour gérer ca
		 */
		$this->output->writeln("Création de la table demarche_tasks");
		Schema::create ( 'demarche_tasks', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'task_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->decimal ( 'cost_administration_currency', 10, 2 );
			$table->decimal ( 'cost_citizen_currency', 10, 2 );
			$table->integer ( 'volume' );
			$table->integer ( 'frequency' );
			$table->decimal ( 'gain_potential_administration', 15, 2 );
			$table->decimal ( 'gain_potential_citizen', 15, 2 );
			$table->decimal ( 'gain_real_administration', 15, 2 );
			$table->decimal ( 'gain_real_citizen', 15, 2 );
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'task_id' )->references ( 'id' )->on ( 'demarchesTasks' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->timestamps ();
			$table->softDeletes ();
		});
	}
}
