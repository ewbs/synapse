<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ReorganizeDemarcheTables extends Migration {
	
	private $unusedCharFields=['volume'];
	private $unusedIntegerFields=['mandatory', 'dematerialized'];
	private $unusedDecimalFields=['gain_potential','gain_real'];
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$output = new ConsoleOutput();
		
		$output->writeln("Modification de la table demarches :");
		Schema::table('demarches', function (Blueprint $table) use($output) {
			
			$output->writeln("\tSuppression des colonnes char : ".implode(", " ,$this->unusedCharFields));
			$table->dropColumn($this->unusedCharFields);
			
			$output->writeln("\tSuppression des colonnes integer : ".implode(", " ,$this->unusedIntegerFields));
			$table->dropColumn($this->unusedIntegerFields);
			
			$output->writeln("\tSuppression des colonnes decimal : ".implode(", " ,$this->unusedDecimalFields));
			$table->dropColumn($this->unusedDecimalFields);
		});
		
		$output->writeln("Création de la table demarchesRevisions");
		Schema::create ( 'demarchesRevisions', function (Blueprint $table) {
			$table->increments ( 'id' )->unsigned ();
			$table->integer ( 'demarche_id' )->unsigned ();
			$table->integer ( 'user_id' )->unsigned ()->index ();
			$table->decimal ( 'gain_potential_administration', 15, 2 )->nullable ();
			$table->decimal ( 'gain_potential_citizen', 15, 2 )->nullable ();
			$table->decimal ( 'gain_real_administration', 15, 2 )->nullable ();
			$table->decimal ( 'gain_real_citizen', 15, 2 )->nullable ();
			$table->foreign ( 'demarche_id' )->references ( 'id' )->on ( 'demarches' )->onDelete ( 'cascade' );
			$table->foreign ( 'user_id' )->references ( 'id' )->on ( 'users' )->onDelete ( 'set null' );
			$table->timestamps ();
			$table->softDeletes ();
		});
		
		$output->writeln("Création de la vue v_lastRevisionFromDemarche");
		DB::statement ('
			CREATE VIEW v_lastRevisionFromDemarche AS
			SELECT
			dr.*
			FROM (
				SELECT demarche_id, MAX(created_at) AS mx
				FROM "demarchesRevisions"
				GROUP BY demarche_id
			) drSub
			JOIN "demarchesRevisions" dr ON dr.demarche_id = drSub.demarche_id
			AND drSub.mx = dr.created_at;
		');
		
		$output->writeln("Création de la vue v_calculatedDemarcheGains");
		DB::statement ('
			CREATE VIEW v_calculatedDemarcheGains AS
			SELECT demarche_id, SUM(gpa) AS gain_potential_administration, SUM(gpc) AS gain_potential_citizen, SUM(gra) AS gain_real_administration, SUM(grc) AS gain_real_citizen FROM 
			(
				SELECT gain_potential_administration AS gpa, gain_potential_citizen AS gpc, gain_real_administration AS gra, gain_real_citizen AS grc, demarche_id FROM "v_lastrevisionpiecesfromdemarche" WHERE deleted_at IS NULL
				UNION
				select gain_potential_administration AS gpa, gain_potential_citizen AS gpc, gain_real_administration AS gra, gain_real_citizen AS grc, demarche_id FROM "v_lastrevisiontasksfromdemarche" WHERE deleted_at IS NULL
			) AS gains
			GROUP BY demarche_id;
		');
		
		$output->writeln("Création de la vue v_demarcheGains");
		DB::statement ('
			CREATE VIEW v_demarcheGains AS
			SELECT d.id AS demarche_id,
			COALESCE(r.gain_potential_administration, c.gain_potential_administration, 0::DECIMAL(15,2)) AS gain_potential_administration, 
			COALESCE(r.gain_potential_citizen, c.gain_potential_citizen, 0::DECIMAL(15,2)) AS gain_potential_citizen, 
			COALESCE(r.gain_real_administration, c.gain_real_administration, 0::DECIMAL(15,2)) AS gain_real_administration, 
			COALESCE(r.gain_real_citizen, c.gain_real_citizen, 0::DECIMAL(15,2)) AS gain_real_citizen
			FROM demarches d
			LEFT JOIN v_lastRevisionFromDemarche r ON d.id=r.demarche_id
			LEFT JOIN v_calculatedDemarcheGains c ON d.id=c.demarche_id;
		');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$output = new ConsoleOutput();
		
		$output->writeln("Suppression de la vue v_demarcheGains");
		DB::statement ( 'DROP VIEW v_demarcheGains' );
		
		$output->writeln("Suppression de la vue v_calculatedDemarcheGains");
		DB::statement ( 'DROP VIEW v_calculatedDemarcheGains' );
		
		$output->writeln("Suppression de la vue v_lastRevisionFromDemarche");
		DB::statement ( 'DROP VIEW v_lastRevisionFromDemarche' );
	
		$output->writeln("Modification de la table demarches :");
		Schema::table('demarches', function (Blueprint $table) use($output) {
			
			$output->writeln("\tRestauration des colonnes char : ".implode(", " ,$this->unusedCharFields));
			foreach($this->unusedCharFields as $col) $table->string ($col, 2048 )->nullable ();
			
			$output->writeln("\tRestauration des colonnes integer : ".implode(", " ,$this->unusedIntegerFields));
			foreach($this->unusedIntegerFields as $col) $table->integer($col)->nullable();
			
			$output->writeln("\tRestauration des colonnes decimal : ".implode(", " ,$this->unusedDecimalFields));
			foreach($this->unusedDecimalFields as $col) $table->decimal($col, 15, 2)->nullable();
		});
		
		$output->writeln("Suppression de la table demarchesRevisions");
		Schema::drop ( 'demarchesRevisions' );
	}
}
