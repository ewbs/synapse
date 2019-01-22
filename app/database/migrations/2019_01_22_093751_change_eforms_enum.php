<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeEformsEnum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE eforms DROP CONSTRAINT eforms_deposable_en_ligne_check, ADD  CONSTRAINT eforms_deposable_en_ligne_check CHECK (deposable_en_ligne::text = ANY (ARRAY['non_communique'::character varying, 'oui_par_mail'::character varying, 'oui_formulaire_web_ou_application_en_ligne'::character varying, 'oui_others'::character varying, 'non'::character varying]::text[]));");
		DB::statement("ALTER TABLE eforms DROP CONSTRAINT eforms_disponible_en_ligne_check, ADD  CONSTRAINT eforms_disponible_en_ligne_check CHECK (disponible_en_ligne::text = ANY (ARRAY['non_communique'::character varying, 'oui_par_mail'::character varying, 'oui_formulaire_web_ou_application_en_ligne'::character varying, 'oui_others'::character varying, 'non'::character varying]::text[]));");
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
