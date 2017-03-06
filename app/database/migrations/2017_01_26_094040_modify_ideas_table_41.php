<?php

use Illuminate\Database\Migrations\Migration;
use Symfony\Component\Console\Output\ConsoleOutput;

class ModifyIdeasTable41 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$output=new ConsoleOutput();
		$output->writeln("Colonne ewbs_member_id rendue optionnelle");
		DB::statement('ALTER TABLE "ideas" ALTER COLUMN "ewbs_member_id" DROP NOT NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE "ideas" ALTER COLUMN "ewbs_member_id" SET NOT NULL;');
	}

}
