<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModifyEwbsActions43 extends Migration {
	
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
		
		$this->output->writeln("Ajout de la valeur standby dans l'enumeration de la colonne state de la table ewbsActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" DROP CONSTRAINT "demarchesActionsRevisions_state_check"');
		$types = ['todo', 'progress', 'done', 'standby', 'givenup'];
		$result = join( ', ', array_map(function( $value ){ return sprintf("'%s'::character varying", $value); }, $types) );
		DB::statement("ALTER TABLE \"ewbsActionsRevisions\" add CONSTRAINT \"demarchesActionsRevisions_state_check\" CHECK (state::text = ANY (ARRAY[$result]::text[]))");
		
		$this->output->writeln("Ajout du lien vers le responsable dans la table ewbsActionsRevisions");
		Schema::table( 'ewbsActionsRevisions', function (Blueprint $table) {
			$table->integer ( 'responsible_id' )->unsigned ()->index()->nullable();
			$table->foreign( 'responsible_id' )->references( 'id' )->on( 'users' )->onDelete( 'set null' );
		});
		
		//FIXME : Ne faudrait-il pas plutôt avoir une contrainte conditionnelle de type check ? (not null si action parente, d'office null si sous-action)
		
		$this->output->writeln("Initialisation par défaut du responsible_id avec le user_id pour la table ewbsActionsRevisions");
		// Note : Cette requête n'est pas mise en seed, car on veut forcer une contrainte not null juste après. Ce n'est qu'une parade à l'impossibilité d'utiliser le default lors de l'ajout de la colonne
		DB::statement('UPDATE "ewbsActionsRevisions" SET responsible_id=user_id');
		
		$this->output->writeln("Passer le responsible_id en not null dans la table ewbsActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" ALTER COLUMN "responsible_id" SET NOT NULL;');
		
		$this->createViewLastRevisionEwbsAction();
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		
		$this->output->writeln("Passage en 'todo' des valeurs 'standby' de la colonne state de la table ewbsActionsRevisions");
		DB::statement('UPDATE "ewbsActionsRevisions" SET state=\'todo\' WHERE state=\'standby\'');
		
		$this->output->writeln("Retrait de la valeur standby dans l'enumeration de la colonne state de la table ewbsActionsRevisions");
		DB::statement('ALTER TABLE "ewbsActionsRevisions" DROP CONSTRAINT "demarchesActionsRevisions_state_check"');
		$types = ['todo', 'progress', 'done', 'givenup'];
		$result = join( ', ', array_map(function( $value ){ return sprintf("'%s'::character varying", $value); }, $types) );
		DB::statement("ALTER TABLE \"ewbsActionsRevisions\" add CONSTRAINT \"demarchesActionsRevisions_state_check\" CHECK (state::text = ANY (ARRAY[$result]::text[]))");
		
		$this->dropViewLastRevisionEwbsAction();
		
		$this->output->writeln("Retrait du lien vers le responsable dans la table ewbsActionsRevisions");
		Schema::table( 'ewbsActionsRevisions', function (Blueprint $table) {
			$table->dropColumn( 'responsible_id' );
		});
		
		$this->createViewLastRevisionEwbsAction();
	}
	
	/**
	 * Supprimer la vue v_lastRevisionEwbsAction
	 *
	 */
	private function dropViewLastRevisionEwbsAction() {
		$this->output->writeln("Suppression de la vue v_lastRevisionEwbsAction");
		DB::statement ('DROP VIEW v_lastRevisionEwbsAction');
	}
	
	/**
	 * Créer ou mettre à jour la vue v_lastRevisionEwbsAction
	 * 
	 */
	private function createViewLastRevisionEwbsAction() {
		$this->output->writeln("Cretation ou maj de la vue v_lastRevisionEwbsAction");
		DB::statement ('
			CREATE OR REPLACE VIEW v_lastRevisionEwbsAction AS
			SELECT
			r.*
			FROM (
				SELECT ewbs_action_id, MAX(created_at) AS mx
				FROM "ewbsActionsRevisions"
				GROUP BY ewbs_action_id
			) rSub
			JOIN "ewbsActionsRevisions" r ON r.ewbs_action_id = rSub.ewbs_action_id
			AND rSub.mx = r.created_at;
		');
	}
}
