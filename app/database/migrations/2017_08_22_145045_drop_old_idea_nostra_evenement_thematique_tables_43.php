<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Ces tables ne sont plus utiles depuis la migration ayant désactivé l'usage de ces tables
 * 
 * @author mgrenson
 * @see CreateIdeasTables
 * @see ModifyIdeasTable40
 */
class DropOldIdeaNostraEvenementThematiqueTables43 extends Migration {

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
		$this->output->writeln("Suppression des tables todelete_idea_nostra_evenement, todelete_idea_nostra_thematiqueabc et todelete_idea_nostra_thematiqueadm");
		Schema::drop('todelete_idea_nostra_evenement');
		Schema::drop('todelete_idea_nostra_thematiqueabc');
		Schema::drop('todelete_idea_nostra_thematiqueadm');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		
		$this->output->writeln("Creation des tables todelete_idea_nostra_evenement, todelete_idea_nostra_thematiqueabc et todelete_idea_nostra_thematiqueadm");
		
		// Creation de la table pivot idees-thematiquesnostra
		Schema::create ( 'todelete_idea_nostra_thematiqueabc', function (Blueprint $table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueabc_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-thematiquesnostra
		Schema::create ( 'todelete_idea_nostra_thematiqueadm', function (Blueprint $table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueadm_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueadm_id' )->references ( 'id' )->on ( 'nostra_thematiquesadm' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-evenementsnostra
		Schema::create ( 'todelete_idea_nostra_evenement', function (Blueprint $table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_evenement_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );
	}
}
