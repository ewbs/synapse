<?php
use Illuminate\Database\Migrations\Migration;
class CreateIdeasTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table des idées
		Schema::create ( 'ideas', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->integer ( 'ewbs_member_id' )->unsigned ()->index ();
			$table->string ( 'name', 1024 )->nullable ();
			$table->text ( 'description' )->nullable ();
			$table->string ( 'ext_contact', 256 )->nullable ();
			$table->text ( 'abc_notrelated' )->nullable ();
			$table->text ( 'freeencoding_nostra_publics' )->nullable ();
			$table->text ( 'freeencoding_nostra_thematiquesabc' )->nullable ();
			$table->text ( 'freeencoding_nostra_thematiquesadm' )->nullable ();
			$table->text ( 'freeencoding_nostra_evenements' )->nullable ();
			$table->text ( 'freeencoding_nostra_demarches' )->nullable ();
			$table->string ( 'doc_source_title', 2048 )->nullable ();
			$table->string ( 'doc_source_page', 256 )->nullable ();
			$table->string ( 'doc_source_link', 1024 )->nullable ();
			$table->integer ( 'prioritary' )->nullable ();
			$table->integer ( 'transversal' )->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' );
			$table->foreign ( 'ewbs_member_id' )->references ( 'id' )->on ( 'ewbs_members' );
		} );
		
		// Creation de la table pivot idees-publicsnostra
		Schema::create ( 'idea_nostra_public', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-thematiquesnostra
		Schema::create ( 'idea_nostra_thematiqueabc', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueabc_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-thematiquesnostra
		Schema::create ( 'idea_nostra_thematiqueadm', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueadm_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueadm_id' )->references ( 'id' )->on ( 'nostra_thematiquesadm' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-evenementsnostra
		Schema::create ( 'idea_nostra_evenement', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_evenement_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-demarchesnostra
		Schema::create ( 'idea_nostra_demarche', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-ministres
		Schema::create ( 'idea_minister', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'minister_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'minister_id' )->references ( 'id' )->on ( 'ministers' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot idees-administrations
		Schema::create ( 'administration_idea', function ($table) {
			$table->integer ( 'idea_id' )->unsigned ()->index ();
			$table->integer ( 'administration_id' )->unsigned ()->index ();
			$table->foreign ( 'idea_id' )->references ( 'id' )->on ( 'ideas' )->onDelete ( 'cascade' );
			$table->foreign ( 'administration_id' )->references ( 'id' )->on ( 'administrations' )->onDelete ( 'cascade' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'administration_idea' );
		Schema::drop ( 'idea_minister' );
		Schema::drop ( 'idea_nostra_demarche' );
		Schema::drop ( 'idea_nostra_evenement' );
		Schema::drop ( 'idea_nostra_thematiqueabc' );
		Schema::drop ( 'idea_nostra_thematiqueadm' );
		Schema::drop ( 'idea_nostra_public' );
		Schema::drop ( 'ideas' );
	}
}
