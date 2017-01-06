<?php

use Illuminate\Database\Console\Migrations\RollbackCommand;
use Symfony\Component\Console\Output\ConsoleOutput;

class TransactionalRollbackCommand extends RollbackCommand {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'migrate:rollback:transaction';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Rollback the last database migration in a single transaction';
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if ( ! $this->confirmToProceed()) return;

		$this->migrator->setConnection($this->input->getOption('database'));

		$pretend = $this->input->getOption('pretend');
		
		$output = new ConsoleOutput();
		DB::beginTransaction();
		try {
			$this->migrator->rollback($pretend);
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
	}
}
