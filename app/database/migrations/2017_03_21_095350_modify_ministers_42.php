<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration vers 4.2
 * 
 * Attention que ce script a la particularité de dépendre de l'installation du package postgresql-contrib, et plus précisément de l'extension btree_gist
 * @author mgrenson
 *
 */
class ModifyMinisters42 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		//$output=new ConsoleOutput();
		//DB::statement('CREATE EXTENSION btree_gist;');
		
		DB::statement('ALTER TABLE governement_minister ADD COLUMN mandate_range daterange NOT NULL DEFAULT(\'[2014-01-01,2017-12-31]\'), ADD EXCLUDE USING gist (minister_id WITH =, governement_id WITH =, mandate_range WITH &&);');
		
		Schema::table( 'governement_minister', function (Blueprint $table) {
			$table->dropColumn(['start', 'end']);
			$table->increments ( 'id' )->unsigned ();
			//$table->primary(['minister_id', 'governement_id', 'mandate_range'], 'governement_minister_pkey');
		});
		
		DB::statement('ALTER TABLE "ministers" ALTER COLUMN "firstname" SET NOT NULL;');
		DB::statement('ALTER TABLE "ministers" ALTER COLUMN "lastname" SET NOT NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		
		DB::statement('ALTER TABLE "ministers" ALTER COLUMN "firstname" DROP NOT NULL;');
		DB::statement('ALTER TABLE "ministers" ALTER COLUMN "lastname" DROP NOT NULL;');
		
		//DB::statement('ALTER TABLE governement_minister DROP CONSTRAINT governement_minister_pkey');
		
		Schema::table( 'governement_minister', function (Blueprint $table) {
			$table->timestamp ( 'start' )->default('2014-01-01 00:00:00');
			$table->timestamp ( 'end' )->default('2017-12-31 23:59:59');
			$table->dropColumn('mandate_range');
			$table->dropColumn('id');
		});
		
		//DB::statement('DROP EXTENSION btree_gist;');
	}
}
