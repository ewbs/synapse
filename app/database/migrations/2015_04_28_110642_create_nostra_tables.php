<?php
use Illuminate\Database\Migrations\Migration;
class CreateNostraTables extends Migration {
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		// Creation de la table des publics
		Schema::create ( 'nostra_publics', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->integer ( 'parent_id' )->unsigned ();
			$table->string ( 'title', 2048 );
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des thématiques ABC
		Schema::create ( 'nostra_thematiquesabc', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->integer ( 'parent_id' )->unsigned ();
			$table->string ( 'title', 2048 );
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des événements
		Schema::create ( 'nostra_evenements', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->string ( 'title', 2048 );
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des démarches (fiches Nostra)
		Schema::create ( 'nostra_demarches', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->string ( 'title', 2048 );
			$table->string ( 'title_long', 2048 );
			$table->string ( 'title_short', 2048 );
			$table->enum ( 'type', array (
					'droit',
					'obligation',
					'aucun' 
			) );
			$table->integer ( 'simplified' )->unsigned ();
			$table->integer ( 'german_version' )->unsigned ();
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des formulaires, liées aux démarches (fiches Nostra)
		Schema::create ( 'nostra_forms', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->string ( 'title', 2048 );
			$table->string ( 'form_id', 64 );
			$table->string ( 'language', 4 );
			$table->string ( 'url', 2048 );
			$table->integer ( 'smart' )->unsigned;
			$table->string ( 'priority', 128 );
			$table->integer ( 'esign' )->unsigned;
			$table->string ( 'format', 128 );
			$table->integer ( 'simplified' )->unsigned ();
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table des documents, liées aux démarches (fiches Nostra)
		Schema::create ( 'nostra_documents', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->string ( 'title', 2048 );
			$table->string ( 'document_id', 64 );
			$table->string ( 'language', 4 );
			$table->string ( 'url', 2048 );
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Creation de la table pivot publics-thematiquesabc
		Schema::create ( 'nostra_public_nostra_thematiqueabc', function ($table) {
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueabc_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot publics-evenements
		Schema::create ( 'nostra_evenement_nostra_public', function ($table) {
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_evenement_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot evenements-thematiquesabc
		Schema::create ( 'nostra_evenement_nostra_thematiqueabc', function ($table) {
			$table->integer ( 'nostra_thematiqueabc_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_evenement_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table des thématiques ADM
		Schema::create ( 'nostra_thematiquesadm', function ($table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'nostra_id', 64 );
			$table->integer ( 'parent_id' )->unsigned ();
			$table->string ( 'title', 2048 );
			$table->timestamp ( 'nostra_state' );
			$table->timestamps ();
			$table->softDeletes ();
		} );
		
		// Création de la table pivot demarches-formulaires
		Schema::create ( 'nostra_demarche_nostra_form', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_form_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_form_id' )->references ( 'id' )->on ( 'nostra_forms' )->onDelete ( 'cascade' );
		} );
		
		// Création de la table pivot demarches-documents
		Schema::create ( 'nostra_demarche_nostra_document', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_document_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_document_id' )->references ( 'id' )->on ( 'nostra_documents' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot demarches-publics
		Schema::create ( 'nostra_demarche_nostra_public', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_public_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_public_id' )->references ( 'id' )->on ( 'nostra_publics' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot demarches-evenements
		Schema::create ( 'nostra_demarche_nostra_evenement', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_evenement_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_evenement_id' )->references ( 'id' )->on ( 'nostra_evenements' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot demarches-thematiquesabc
		Schema::create ( 'nostra_demarche_nostra_thematiqueabc', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueabc_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueabc_id' )->references ( 'id' )->on ( 'nostra_thematiquesabc' )->onDelete ( 'cascade' );
		} );
		
		// Creation de la table pivot demarches-thematiquesadm
		Schema::create ( 'nostra_demarche_nostra_thematiqueadm', function ($table) {
			$table->integer ( 'nostra_demarche_id' )->unsigned ()->index ();
			$table->integer ( 'nostra_thematiqueadm_id' )->unsigned ()->index ();
			$table->foreign ( 'nostra_demarche_id' )->references ( 'id' )->on ( 'nostra_demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'nostra_thematiqueadm_id' )->references ( 'id' )->on ( 'nostra_thematiquesadm' )->onDelete ( 'cascade' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop ( 'nostra_demarche_nostra_thematiqueadm' );
		Schema::drop ( 'nostra_demarche_nostra_thematiqueabc' );
		Schema::drop ( 'nostra_demarche_nostra_evenement' );
		Schema::drop ( 'nostra_demarche_nostra_public' );
		Schema::drop ( 'nostra_evenement_nostra_thematiqueabc' );
		Schema::drop ( 'nostra_evenement_nostra_public' );
		Schema::drop ( 'nostra_public_nostra_thematiqueabc' );
		Schema::drop ( 'nostra_demarche_nostra_form' );
		Schema::drop ( 'nostra_demarche_nostra_document' );
		Schema::drop ( 'nostra_thematiquesadm' );
		Schema::drop ( 'nostra_documents' );
		Schema::drop ( 'nostra_forms' );
		Schema::drop ( 'nostra_demarches' );
		Schema::drop ( 'nostra_evenements' );
		Schema::drop ( 'nostra_thematiquesabc' );
		Schema::drop ( 'nostra_publics' );
	}
}
