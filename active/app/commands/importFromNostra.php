<?php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class importFromNostra extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron:importFromNostra';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';
	
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
	 * Il y a une grosse difficulté à prendre en compte lors de l'import.
	 * La premiere version de ce script refesait un sync() sur les objets importés.
	 * MAIS cela éclatait les relations entre les objets de Damus.
	 * Il faut donc bien faire attention à ce genre de relations maintenant.
	 *
	 * @return mixed
	 */
	public function fire() {
		$nostraWS_publicsCibles = Config::get ( 'app.nostraWS_publicsCibles' );
		$nostraWS_thematiques = Config::get ( 'app.nostraWS_thematiques' );
		$nostraWS_evenementsDeclencheurs = Config::get ( 'app.nostraWS_evenementsDeclencheurs' );
		$nostraWS_demarches = Config::get ( 'app.nostraWS_demarches' );
		
		print ("\n[" . date ( "d/m/Y H:i:s", time () ) . "] Demarrage de l'import Nostra\n") ;
		
		/*
		 * IMPORTATION DES PUBLICS CIBLES
		 */
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] Import des publics cibles\n") ;
		$json = json_decode ( file_get_contents ( $nostraWS_publicsCibles ) );
		$count = 0;
		foreach ( $json->{'publics-cible'} as $pc ) {
			$opc = NostraPublic::firstOrNew ( array (
					"nostra_id" => $pc->{'public-cible'}->tid 
			) );
			$opc->nostra_id = $pc->{'public-cible'}->tid;
			$opc->nostra_titre = $pc->{'public-cible'}->titre;
			$opc->source = 'nostra';
			$opc->save ();
			$count ++;
		}
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] $count publics importes\n") ;
		
		/*
		 * IMPORTATION DES THEMATIQUES, selon les publics cibles
		 */
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] Import des thematiques\n") ;
		$arrayNostraPublics = NostraPublic::all ();
		foreach ( $arrayNostraPublics as $nostraPublic ) {
			print ("[" . date ( "d/m/Y H:i:s", time () ) . "] >> " . $nostraPublic->nostra_titre . " ") ;
			$json = json_decode ( file_get_contents ( str_replace ( "{{publicId}}", $nostraPublic->nostra_id, $nostraWS_thematiques ) ) );
			$count = 0;
			$arrayThematiquesIds = array (); // stocker les ids de thématiques trouvés pour les attacher au public
			foreach ( $json->{'themes_abc'} as $pc ) {
				$opc = NostraThematique::firstOrNew ( array (
						"nostra_id" => $pc->{'theme_abc'}->tid 
				) );
				$opc->nostra_id = $pc->{'theme_abc'}->tid;
				$opc->nostra_titre = $pc->{'theme_abc'}->titre;
				$opc->source = 'nostra';
				$opc->save ();
				$count ++;
				array_push ( $arrayThematiquesIds, $opc->id );
			}
			// avant le sync, on récupère les liens existant entre ce public et des thématiques Damus.
			// on les fusionne avec les thématiques nostra et on sync le tout
			$nostraPublic->nostraThematiques ()->sync ( array_merge ( $arrayThematiquesIds, $nostraPublic->linkedDamusThematiquesIds () ) );
			print (" $count thematiques\n") ;
		}
		
		/*
		 * IMPORTATION DES EVENEMENTS, selon les publics cibles & les thématiques
		 */
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] Import des evenements declencheurs\n") ;
		foreach ( $arrayNostraPublics as $nostraPublic ) {
			$arrayNostraThematiques = $nostraPublic->nostraThematiques ()->get ();
			$arrayAllEvenementsIds = array (); // on va faire un sync de la relation public/evenements grace à ce tableau d'id (il contiendra les ids de tous les événements trouvé dans la boucle ci-dessous)
			foreach ( $arrayNostraThematiques as $nostraThematique ) {
				print ("[" . date ( "d/m/Y H:i:s", time () ) . "] >> " . $nostraPublic->nostra_titre . " >> " . $nostraThematique->nostra_titre . " ") ;
				$json = json_decode ( file_get_contents ( str_replace ( "{{publicId}}", $nostraPublic->nostra_id, str_replace ( "{{thematiqueId}}", $nostraThematique->nostra_id, $nostraWS_evenementsDeclencheurs ) ) ) );
				$arrayEvenementsIds = array (); // stocker les ids des evenements trouvés pour les attacher au public (et on stockera tout le tableau dans le tableau général créé ci-dessus (allId)
				foreach ( $json->{'evt. decs'} as $pc ) {
					$opc = NostraEvenement::firstOrNew ( array (
							"nostra_id" => $pc->{'evt. dec.'}->nid 
					) );
					$opc->nostra_id = $pc->{'evt. dec.'}->nid;
					$opc->nostra_titre = $pc->{'evt. dec.'}->titre;
					$opc->source = 'nostra';
					$opc->save ();
					if (! in_array ( $opc->id, $arrayEvenementsIds )) {
						array_push ( $arrayEvenementsIds, $opc->id );
					}
					if (! in_array ( $opc->id, $arrayAllEvenementsIds )) {
						array_push ( $arrayAllEvenementsIds, $opc->id );
					}
				}
				// if ( count($arrayEvenementsIds) ) { $nostraThematique->nostraEvenements()->attach($arrayEvenementsIds); }
				// avant le sync, on récupère les liens existant entre cette thématiques et des evenements Damus.
				// on les fusionne avec les thématiques nostra et on sync le tout
				$nostraThematique->nostraEvenements ()->sync ( array_merge ( $arrayEvenementsIds, $nostraThematique->linkedDamusEvenementsIds () ) );
				print (count ( $arrayEvenementsIds ) . " evenements\n") ;
			}
			$nostraPublic->nostraEvenements ()->sync ( array_merge ( $arrayAllEvenementsIds, $nostraPublic->linkedDamusEvenementsIds () ) );
		}
		
		/*
		 * IMPORTATION DES DEMARCHES
		 */
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] Import des demarches\n") ;
		$arrayNostraEvenements = NostraEvenement::all ();
		foreach ( $arrayNostraEvenements as $nostraEvenement ) {
			print ("[" . date ( "d/m/Y H:i:s", time () ) . "] >> " . $nostraEvenement->nostra_titre . " ") ;
			$json = json_decode ( file_get_contents ( str_replace ( "{{evenementId}}", $nostraEvenement->nostra_id, $nostraWS_demarches ) ) );
			$count = 0;
			$themesIds = $nostraEvenement->getNostraThematiquesIds (); // pour le lien demarches-thematiques
			$publicsIds = $nostraEvenement->getNostraPublicsIds (); // on prend les publics liés à cet événement , pour faire le lien demarches-publics
			$arrayDemarchesIds = array (); // stocker les ids de démarches trouvés pour les attacher à l'événement déclencheur
			foreach ( $json->{'demarches'} as $pc ) {
				$opc = NostraDemarche::firstOrNew ( array (
						"nostra_id" => $pc->{'demarche'}->nid 
				) );
				$opc->nostra_id = $pc->{'demarche'}->nid;
				if (isset ( $pc->{'demarche'}->titre )) {
					$opc->nostra_titre = $pc->{'demarche'}->titre;
					$opc->source = 'nostra';
					$opc->save ();
					// on fait le lien demarches-thematiques (on ne peut pas avoir cela via les WS de nostra 1.x)
					$opc->nostraThematiques ()->sync ( $themesIds );
					// on fait le lien demarches-publics (pas accessible non plus dans nostra)
					$opc->nostraPublics ()->sync ( $publicsIds );
					$count ++;
					array_push ( $arrayDemarchesIds, $opc->id );
				}
			}
			// on fait le lien demarches-evenements
			$nostraEvenement->nostraDemarches ()->sync ( array_merge ( $arrayDemarchesIds, $nostraEvenement->linkedDamusDemarchesIds () ) );
			print (" $count demarches\n") ;
		}
		
		print ("[" . date ( "d/m/Y H:i:s", time () ) . "] Fin de l'importation\n") ;
	}
	
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		// pas d'options ... pour le moment
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
		// pas d'options ... pour le moment
		return array ()
		// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		;
	}
}
