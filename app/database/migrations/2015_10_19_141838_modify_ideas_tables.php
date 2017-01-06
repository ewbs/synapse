<?php

/**
 * On ne modifie pas la table ideas ici, ni ses tables associées
 * Par contre on en ajoute des nouvelles
 */
use Illuminate\Database\Migrations\Migration;
class ModifyIdeasTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		
		/*
		 * ETATS D'UNE IDEE
		 */
		
		// TABLE DES ETATS
		Schema::create ( 'ideaStates', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'name', 64 );
			$table->integer ( 'order' )->default ( 0 );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// TABLES DE JOINTURE ENTRE ETAT ET IDEES
		// les 3 FK ne volontairement pas des index, car on peut avoir des doublons (ex: on revient à un état
		// antérieur --> la paire "idee/etat/user" peut être identique, seule la date change;
		// on utilise donc une PK distincte pour gérer ca
		Schema::create ( 'ideaStateModifications', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'idea_id' )->unsigned ();
			$table->integer ( 'idea_state_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'idea_state_id' )->references ( 'id' )->on ( 'ideaStates' )->onDelete ( 'cascade' );
			//FIXME : veut-on vraiment supprimer une ideaStateModifications quand le user correspondant est supprimé ? ne veut-on pas le mettre à null ?
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'cascade' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		/*
		 * COMMENTAIRES SUR UNE IDEE
		 */
		Schema::create ( 'ideaComments', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->integer ( 'idea_id' )->unsigned ();
			$table->text ( 'comment' );
			//FIXME : veut-on vraiment supprimer une ideaComments quand le user correspondant est supprimé ? ne veut-on pas le mettre à null ?
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'cascade' );
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'ideaComments' );
		Schema::drop ( 'ideaStateModifications' );
		Schema::drop ( 'ideaStates' );
	}
}
