<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreatesEformsTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		/*
		 * La table eforms forme le catalogue des formulaires.
		 * On travaille directement sur un formulaire. Il n'est donc pas nécessaire de créer une table de formulaire dans le cadre de démarche ou autre.
		 * Si un formulaire existe dans le catalogue, on travaille directement dessus (contrairement aux annexes)
		 */
		$output->writeln("Création de la table eforms");
		Schema::create ( 'eforms', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'form_id', 64 )->nullable()->unique();
			$table->integer ( 'nostra_form_id')->unsigned()->nullable()->unique();
			$table->string ( 'title', 2048 )->nullable()->unique();
			$table->text ( 'description' )->nullable();
			$table->string ( 'language', 4 )->nullable();
			$table->string ( 'url', 2048 )->nullable();
			$table->integer ( 'smart' )->unsigned()->nullable();
			$table->string ( 'priority', 128 )->nullable();
			$table->integer ( 'esign' )->unsigned()->nullable();
			$table->string ( 'format', 128 )->nullable();
			$table->integer ( 'simplified' )->unsigned ()->nullable();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'nostra_form_id' )->references ( 'id' )->on ( 'nostra_forms' )->onDelete ( 'set null' );
		});
		
		/*
		 * Versionning des eforms
		 */
		$output->writeln("Création de la table eformsRevisions");
		Schema::create ( 'eformsRevisions', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ()->nullable();
			$table->integer ( 'eform_id' )->unsigned ();
			$table->text('comment')->nullable ();
			$table->integer ( 'current_state_id' )->unsigned ()->nullable ();
			$table->integer ( 'next_state_id' )->unsigned ()->nullable ();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'current_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
			$table->foreign ( 'next_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'set null' );
			$table->foreign ( 'eform_id' )->references ( 'id' )->on ( 'eforms' )->onDelete ( 'cascade' );
		});
		
		/*
		 * Vue pour obtenir les formulaires dans leurs dernières révisions
		 */
		$output->writeln("Création de la vue v_lastRevisionEforms");
		DB::statement ('
			CREATE VIEW v_lastRevisionEforms AS
			SELECT
				r.*
			FROM (
				SELECT eform_id, MAX(created_at) AS mx
				FROM "eformsRevisions"
				GROUP BY eform_id
			) rsub
			JOIN "eformsRevisions" r ON r.eform_id = rsub.eform_id
			AND rsub.mx = r.created_at;
		');

		/*
		 * Cette table représente le catalogue des annexes.
		 * Quand on parle d'une annexe liée à un formulaire, on utilise la table eform_annexe.
		 * La clé piece_id est un lien vers une piece (sens Pièce justificative, demarchePiece dans le catalogue des pièces). Nullable, donc on ne crée pas d'index
		 */
		$output->writeln("Création de la table annexes");
		Schema::create ( 'annexes', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->string ( 'title', 2048 )->unique();
			$table->integer( 'piece_id' )->unsigned ()->nullable()->unique();
			$table->text ( 'description' )->nullable();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'piece_id' )->references ( 'id' )->on ( 'demarchesPieces' )->onDelete ( 'set null' );
		});
		
		/*
		 * Cette table représente une annexe liée à un formulaire
		 * Comme pour les demarches_pieces et demarches_tasks, la révision se fait dans cette table. La dernière révision étant la dernière créée.
		 * On se facilite la vie avec des VIEWS (plus bas)
		 */
		$output->writeln("Création de la table annexe_eform");
		Schema::create ( 'annexe_eform', function (Blueprint $table) {
			$table->increments( 'id' )->unsigned();
			$table->integer ( 'eform_id' )->unsigned();
			$table->integer ( 'annexe_id' )->unsigned();
			$table->integer ( 'current_state_id' )->unsigned ()->nullable ();
			$table->integer ( 'next_state_id' )->unsigned ()->nullable ();
			$table->integer ( 'user_id' )->unsigned ()->nullable();
			$table->text( 'comment' )->nullable();
			$table->timestamps ();
			$table->softDeletes ();
			$table->foreign ( 'eform_id' )->references ( 'id' )->on ( 'eforms' )->onDelete ( 'cascade' );
			$table->foreign ( 'annexe_id' )->references ( 'id' )->on ( 'annexes' )->onDelete ( 'cascade' );
			$table->foreign ( 'current_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
			$table->foreign ( 'next_state_id' )->references ( 'id' )->on ( 'demarchesPiecesStates' )->onDelete ( 'set null' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'set null' );
		});
		
		/*
		 * Vue pour obtenir les annexes liées à un formulaire dans leur dernière révision
		 * (donc sans l'historique des modifications)
		 */
		$output->writeln("Création de la vue v_lastRevisionAnnexes");
		DB::statement ('
			CREATE VIEW v_lastRevisionAnnexes AS
			SELECT 
				r.*,
				rsub.mx
			FROM (
				SELECT annexe_id, eform_id, MAX(created_at) AS mx
				FROM annexe_eform
				GROUP BY annexe_id, eform_id
			) rsub
			JOIN annexe_eform r ON r.annexe_id = rsub.annexe_id 
			AND rsub.mx = r.created_at 
			AND r.eform_id = rsub.eform_id;'
		);

		/*
		 * Ajout dans les actions de la colonne pour les eforms
		 */
		$output->writeln("Modification de la table ewbsActions pour les eforms");
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->integer ( 'eform_id' )->unsigned ()->index()->nullable();
			$table->foreign ( 'eform_id' )->references ( 'id' )->on ( 'eforms' )->onDelete ( 'set null' );
		});

		/*
		 * Formulaires liés aux démarches
		 * Table de jointure
		 */
		$output->writeln("Création de la table demarche_eform");
		Schema::create ( 'demarche_eform', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'eform_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ();
			$table->text ( 'comment' )->nullable ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'eform_id' )->references ( 'id' )->on ( 'eforms' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'set null' );
			$table->timestamps ();
			$table->softDeletes ();
		});

		/*
		 * Vue pour obtenir les eforms liés à une démarche dans leur dernière révision
		 * (donc sans l'historique des modifications)
		 */
		$output->writeln("Création de la vue v_lastRevisionDemarcheEform");
		DB::statement ('
			CREATE VIEW v_lastRevisionDemarcheEform AS
			SELECT
				r.*,
				rsub.mx
			FROM (
				SELECT demarche_id, eform_id, MAX(created_at) AS mx
				FROM demarche_eform
				GROUP BY demarche_id, eform_id
			) rsub
			JOIN demarche_eform r ON r.demarche_id = rsub.demarche_id
			AND rsub.mx = r.created_at
			AND r.eform_id = rsub.eform_id;'
		);
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();

		$output->writeln("Modification de la table ewbsActions pour les eforms");
		Schema::table('ewbsActions', function (Blueprint $table) {
			$table->dropColumn('eform_id');
		});

		$output->writeln("Suppression de la vue v_lastRevisionDemarcheEform");
		DB::statement ( 'DROP VIEW v_lastRevisionDemarcheEform' );

		$output->writeln("Suppression de la table demarche_eform");
		Schema::drop ( 'demarche_eform' );

		$output->writeln("Suppression de la vue v_lastRevisionAnnexes");
		DB::statement ( 'DROP VIEW v_lastRevisionAnnexes' );
		
		$output->writeln("Suppression de la table annexe_eform");
		Schema::drop ( 'annexe_eform' );
		
		$output->writeln("Suppression de la table annexes");
		Schema::drop ( 'annexes' );

		$output->writeln("Suppression de la vue v_lastRevisionEforms");
		DB::statement ( 'DROP VIEW v_lastRevisionEforms' );

		$output->writeln("Suppression de la table eformsRevisions");
		Schema::drop ( 'eformsRevisions' );

		$output->writeln("Suppression de la table eforms");
		Schema::drop ( 'eforms' );
	}
}
