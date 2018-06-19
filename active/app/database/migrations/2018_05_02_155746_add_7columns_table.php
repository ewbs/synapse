<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add7columnsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->enum('disponible_en_ligne', [
				'non_communique',
				'oui_formulaire_telechargeable',
				'oui_formulaire_web_ou_application_en_ligne',
				'non'
			])->nullable();

			$table->enum('deposable_en_ligne', [
				'non_communique',
				'oui_par_mail',
				'oui_formulaire_web_ou_application_en_ligne',
				'non'
			])->nullable();

			$table->enum('dematerialisation', [
				'non_communique',
				'deja_effectue',
				'oui',
				'non'
			])->nullable();

			$table->enum('intervention_ewbs', [
				'non_communique',
				'oui',
				'non'
			])->nullable();

			$table->string('references_contrat_administration')->nullable();

			$table->string('remarques')->nullable();

			$table->string('dematerialisation_date')->nullable();

			$table->string('dematerialisation_canal')->nullable();

			$table->string('dematerialisation_canal_autres')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('eforms', function(Blueprint $table)
		{
			$table->dropColumn('disponible_en_ligne');
			$table->dropColumn('deposable_en_ligne');
			$table->dropColumn('dematerialisation');
			$table->dropColumn('intervention_ewbs');
			$table->dropColumn('references_contrat_administration');
			$table->dropColumn('remarques');
			$table->dropColumn('dematerialisation_date');
		});
	}

}
