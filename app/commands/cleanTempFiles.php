<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class cleanTempFiles extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron:cleanTempFiles';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Nettoyage du dossier /public/temp';
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		try {
			Log::info ( "Nettoyage des fichiers temporaires (générés pour le public)" );
			$path = public_path () . '/temp';
			if (File::cleanDirectory ( $path )) {
				Log::info ( "Nettoyage terminé" );
			} else {
				Log::error ( 'Erreur durant le nettoyage' );
			}
		} catch ( Exception $ex ) {
			Log::error ( $ex->getMessage () );
		}
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return array ()
		// array('example', InputArgument::REQUIRED, 'An example argument.'),
		;
	}
	
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		return array ()
		// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		;
	}
}
