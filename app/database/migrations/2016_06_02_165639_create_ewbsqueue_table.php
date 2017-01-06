<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Symfony\Component\Console\Output\ConsoleOutput;

class CreateEwbsqueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$output = new ConsoleOutput();
		$output->writeln("Création de la table laq_async_queue");
        Schema::create('laq_async_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(0);
            $table->integer('retries')->default(0);
            $table->integer('delay')->default(0);
            $table->text('queue')->default('default');
            $table->longText('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$output = new ConsoleOutput();
		$output->writeln("Suppression de la table laq_async_queue");
        Schema::drop('laq_async_queue');
    }
}
