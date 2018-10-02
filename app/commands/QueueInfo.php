<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class QueueInfo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:queueinfo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Show info about the queue data.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if($this->option('clear')){
			DB::table('laq_async_queue')->truncate();
			echo "la queue a été nettoyée\n";
		} else {
			$datas = DB::select('SELECT * FROM laq_async_queue ORDER BY created_at DESC');
			foreach ($datas as $data){
				echo $data->id." : ".$data->payload."\n\n";
			}
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('clear', null, InputOption::VALUE_NONE, 'clear queue.', null),
		);
	}

}
