<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;


class ModifyIdeasTable40 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Modification de la table ideas et de ses relations");

		// on renomme les tables qui ne servent plus à rien.
		// on les supprimera dans une prochaine release

		Schema::table('idea_nostra_thematiqueabc', function(Blueprint $table) {
			$table->dropForeign(['idea_id']);
			$this->dropForeignIfExists($table, 'nostra_thematiqueabc_id');// Méthode sur mesure, car cette foreign n'existe pas dans la DB de prod... ou a été supprimée en cours de route par le dtic :-/
		});
		Schema::rename('idea_nostra_thematiqueabc', 'todelete_idea_nostra_thematiqueabc');

		Schema::table('idea_nostra_thematiqueadm', function(Blueprint $table) {
			$table->dropForeign(['idea_id']);
			$table->dropForeign(['nostra_thematiqueadm_id']);
		});
		Schema::rename('idea_nostra_thematiqueadm', 'todelete_idea_nostra_thematiqueadm');

		Schema::table('idea_nostra_evenement', function(Blueprint $table) {
			$table->dropForeign(['idea_id']);
			$table->dropForeign(['nostra_evenement_id']);
		});
		Schema::rename('idea_nostra_evenement', 'todelete_idea_nostra_evenement');


		// et on ajoute la colonne "référence"
		Schema::table('ideas', function(Blueprint $table) {
			$table->text('reference')->after('doc_source_link')->nullable();
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
		$output->writeln("Annulation des modification de la table ideas");

		Schema::rename('todelete_idea_nostra_thematiqueabc', 'idea_nostra_thematiqueabc');
		Schema::rename('todelete_idea_nostra_thematiqueadm', 'idea_nostra_thematiqueadm');
		Schema::rename('todelete_idea_nostra_evenement', 'idea_nostra_evenement');

		Schema::table ( 'idea_nostra_thematiqueabc', function (Blueprint $table) {
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
		} );

		Schema::table ( 'idea_nostra_thematiqueadm', function (Blueprint $table) {
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueadm_id' )->references ( 'id' )->on ( 'nostra_thematiquesadm' )->onDelete ( 'cascade' );
		} );

		Schema::table ( 'idea_nostra_evenement', function (Blueprint $table) {
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );

		Schema::table('ideas', function(Blueprint $table) {
			$table->dropColumn('reference');
		});
	}
	
	private function dropForeignIfExists(Blueprint $table, $column) {
		foreach(DB::select('
			SELECT tc.constraint_name
			FROM information_schema.table_constraints AS tc 
			JOIN information_schema.key_column_usage AS kcu ON tc.constraint_name = kcu.constraint_name
			WHERE constraint_type = \'FOREIGN KEY\' AND tc.table_name=:table and kcu.column_name=:column',
			['table'=>$table->getTable(), 'column'=>$column])
		as $constraint) {
			$table->dropForeign($constraint->constraint_name);
		}
	}
}
