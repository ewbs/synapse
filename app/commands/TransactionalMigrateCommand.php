<?php

use Illuminate\Database\Console\Migrations\MigrateCommand;
use Symfony\Component\Console\Output\ConsoleOutput;

class TransactionalMigrateCommand extends MigrateCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate:transaction';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the database migrations in a single transaction';
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if ( ! $this->confirmToProceed()) return;

		$this->prepareDatabase();

		// The pretend option can be used for "simulating" the migration and grabbing
		// the SQL queries that would fire if the migration were to be run against
		// a database for real, which is helpful for double checking migrations.
		$pretend = $this->input->getOption('pretend');

		$path = $this->getMigrationPath();
		
		$output = new ConsoleOutput();
		DB::beginTransaction();
		try {
			$this->migrator->run($path, $pretend);
			DB::commit();
		}
		catch(Exception $e) {
			DB::rollBack();
			$output->writeln(Lang::get('general.migrate.error'));
			throw new Exception($e);
		}
		
		// Once the migrator has run we will grab the note output and send it out to
		// the console screen, since the migrator itself functions without having
		// any instances of the OutputInterface contract passed into the class.
		foreach ($this->migrator->getNotes() as $note)
		{
			$this->output->writeln($note);
		}

		// Finally, if the "seed" option has been given, we will re-run the database
		// seed task to re-populate the database, which is convenient when adding
		// a migration and a seed at the same time, as it is only this command.
		if ($this->input->getOption('seed'))
		{
			$this->call('db:seed', ['--force' => true]);
		}
	}
}
