<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEformsEnum2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE eforms DROP CONSTRAINT eforms_intervention_ewbs_check, ADD  CONSTRAINT eforms_intervention_ewbs_check CHECK (intervention_ewbs::text = ANY (ARRAY['non_communique'::character varying, 'oui'::character varying, 'non'::character varying, 'a_demarer'::character varying, 'en_cours'::character varying, 'finie'::character varying]::text[]));");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
