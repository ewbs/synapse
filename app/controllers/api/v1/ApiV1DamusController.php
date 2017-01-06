<?php

/**
 * Controleur pour l'api de Synapse
 * partie Nostra-Damus
 *
 *
 * Ce controlleur est appelé par l'application au travers de requetes Ajax.
 * Cela sert à connaitre les liens entre des éléments de Damus. Par exemple, connaitre les thématiques liées à un public
 * ou bien les événements liés à une démarche
 *
 */
use Illuminate\Database\Eloquent\ModelNotFoundException;


class ApiV1DamusController extends Controller {


	/**
	 * Dummy call.
	 * Juste vérifier que l'api répond correctement. Accessoirement ca retourne la version.
	 * @return string
	 */
	public function getIndex() {
		return ('Hello, je suis l\'API Damus de Synapse. Version 1.');
	}

	/**
	 * Obtenir la liste des publics cibles
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getPublics() {
		$aNostraPublics = NostraPublic::root ()->get ();
		foreach ( $aNostraPublics as $p ) {
			$p = $p->traverse ();
		}
		return Response::json ( array (
				'error' => false,
				'publics' => $aNostraPublics->toArray () 
		), 200 );
	}

	/**
	 * Obtenir la liste des thématiques ABC, sur base d'un public
	 * @param $publicId
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getThematiquesABC($publicId) {
		
		/* comme lors de l'import on a fait les liens avec les publics enfant, parent etc ... pas besoin de le faire ici */
		try {
			$nostraPublic = NostraPublic::findOrFail ( $publicId );
			$aNostraThematiques = $nostraPublic->nostraRootThematiquesabc;
			foreach ( $aNostraThematiques as $t ) {
				$t = $t->traverse ();
			}
			return Response::json ( array (
					'error' => false,
					'thematiques' => $aNostraThematiques->toArray () 
			), 200 );
		} catch ( ModelNotFoundException $e ) {
			Log::error($e);
			return Response::json ( array (
					'error' => true,
					'thematiques' => null 
			), 200 );
		}
	}

	/**
	 * Obtenir la liste des thématiques administratives
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getThematiquesADM() {
		
		/* comme lors de l'import on a fait les liens avec les publics enfant, parent etc ... pas besoin de le faire ici */
		try {
			$aNostraThematiques = NostraThematiqueadm::root ()->orderBy ( 'title' )->get ();
			foreach ( $aNostraThematiques as $t ) {
				$t = $t->traverse ();
			}
			return Response::json ( array (
					'error' => false,
					'thematiques' => $aNostraThematiques->toArray () 
			), 200 );
		} catch ( ModelNotFoundException $e ) {
			Log::error($e);
			return Response::json ( array (
					'error' => true,
					'thematiques' => null 
			), 200 );
		}
	}

	/**
	 * Obtenir la liste des événements déclencheurs, sur base d'un public et d'une thématique abc
	 * @param $public
	 * @param int $thematiqueabc
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getEvenements($public, $thematiqueabc = 0) {
		try {
			
			if ($thematiqueabc == 0) {
				$nostraPublic = NostraPublic::findOrFail ( $public );
				$arrayNostraEvenements = DB::table ( 'nostra_evenements' )->join ( 'nostra_evenement_nostra_public', function ($join) use($public) {
					$join->on ( 'nostra_evenement_nostra_public.nostra_evenement_id', '=', 'nostra_evenements.id' )->where ( 'nostra_public_id', '=', $public );
				} )->get ();
				foreach ( $arrayNostraEvenements as $e ) {
					$nostraEvenement = NostraEvenement::find ( $e->id );
					$e->nostra_thematiqueabc_id = $nostraEvenement->getNostraThematiquesabcIds ();
				}
			} else {
				$nostraPublic = NostraPublic::findOrFail ( $public );
				// $nostraThematiquesabc = NostraThematiqueabc::findOrFail($thematiqueabc);
				// on va passer le param en clair à la DB car laravel 4.2 ne support pas
				// le wherein dans un join (cf plus bas) ... c'est dangereux. Il faut s'assurer
				// qu'on ne passe pas de la merde.
				// on peut recevoir un chiffre ou des chiffres séparés par des virgules.
				// test simple : faire un string ou on enleve les "," et tester si c un nombre :-)
				$stringToValidate = str_replace ( ",", "", $thematiqueabc );
				if (! is_numeric ( $stringToValidate )) {
					throw new Exception ( "Ooops" );
				}
				
				$arrayNostraEvenements = DB::table ( 'nostra_evenements' )->join ( 'nostra_evenement_nostra_public', function ($join) use($public) {
					$join->on ( 'nostra_evenement_nostra_public.nostra_evenement_id', '=', 'nostra_evenements.id' )->where ( 'nostra_public_id', '=', $public );
				} )->join ( 'nostra_evenement_nostra_thematiqueabc', function ($join) use($thematiqueabc) {
					$join->on ( 'nostra_evenement_nostra_thematiqueabc.nostra_evenement_id', '=', 'nostra_evenements.id' )->on ( 'nostra_thematiqueabc_id', 'in', DB::raw ( '(' . $thematiqueabc . ')' ) ); // Laralve 4.2 ne supporte pas le WhereIN
				} )->get ();
			}
			return Response::json ( array (
					'error' => false,
					'evenements' => $arrayNostraEvenements 
			), // pas besoin de ->toArray() car on a recu un tableau du querBuilder, pas une Collection.
200 );
		} catch ( Exception $e ) {
			Log::error($e);
			return Response::json ( array (
					'error' => true,
					'evenements' => null 
			), 200 );
		}
	}
	
	/**
	 * La fonction retourne une intersection : démarches correspondants à tous les critères
	 * Paramètres acceptés dans l'url : publics, thematiquesabc, evenemements, thematiquesadm
	 * Exemple : api/v1/damus/demarches?publics=1,2,3&thematiquesabc=1&evenements=1,10&thematiquesadm=88,74
	 * 
	 * @return type
	 */
	public function getDemarches() {
		
		// Méthode :
		// 1. Charger les publics si nécessaires
		// 2. Charger les thématiques ABC si nécessaires
		// 3. Charger les événements si nécessaires
		// 4. Charger les thématiques ADM si nécessaires
		// 5. Produire des tableaux d'ids de démarches pour chaque collection
		// 6. Trouver les intersections entre les tableaux d'ids
		// 7. Charger les objets NostraDemarches et les retourner
		try {
			
			$arraysToIntersects = array ();
			
			// 1.
			if (Input::has ( 'publics' )) {
				$aPublicsIds = explode ( ',', Input::get ( 'publics' ) );
				$aNostraPublics = NostraPublic::findMany ( $aPublicsIds );
				foreach ( $aNostraPublics as $p ) {
					array_push ( $arraysToIntersects, $p->getNostraDemarchesIds () );
				}
			}
			
			// 2.
			if (Input::has ( 'thematiquesabc' )) {
				$aThematiquesabcIds = explode ( ',', Input::get ( 'thematiquesabc' ) );
				$aNostraThematiquesabc = NostraThematiqueabc::findMany ( $aThematiquesabcIds );
				foreach ( $aNostraThematiquesabc as $p ) {
					array_push ( $arraysToIntersects, $p->getNostraDemarchesIds () );
				}
			}
			
			// 3.
			if (Input::has ( 'evenements' )) {
				$aEvenementsIds = explode ( ',', Input::get ( 'evenements' ) );
				$aEvenements = NostraEvenement::findMany ( $aEvenementsIds );
				foreach ( $aEvenements as $p ) {
					array_push ( $arraysToIntersects, $p->getNostraDemarchesIds () );
				}
			}
			
			// 4.
			if (Input::has ( 'thematiquesadm' )) {
				$aThematiquesadmIds = explode ( ',', Input::get ( 'thematiquesadm' ) );
				$aNostraThematiquesadm = NostraThematiqueadm::findMany ( $aThematiquesadmIds );
				foreach ( $aNostraThematiquesadm as $p ) {
					array_push ( $arraysToIntersects, $p->getNostraDemarchesIds () );
				}
			}
			
			// 5.
			// est fait dans les précédents
			
			// 6.
			if (count ( $arraysToIntersects ) > 1) {
				$intersectedArrays = call_user_func_array ( 'array_intersect', $arraysToIntersects );
			} else {
				$intersectedArrays = $arraysToIntersects [0];
			}
			
			// 7.
			$aNostraDemarches = NostraDemarche::findMany ( $intersectedArrays );
			return Response::json ( array (
					'error' => false,
					'demarches' => $aNostraDemarches->toArray () 
			), 200 );
		} catch ( ModelNotFoundException $e ) {
			Log::error($e);
			return Response::json ( array (
					'error' => true,
					'demarches' => null 
			), 200 );
		}
	}
	public function getThematiquesByDemarche($demarcheId) {
		try {
			$nostraDemarche = NostraDemarche::findOrFail ( $demarcheId );
			$arrayNostraThematiques = $nostraDemarche->nostraThematiques;
			return Response::json ( array (
					'error' => false,
					'thematiques' => $arrayNostraThematiques->toArray () 
			), 200 );
		} catch ( ModelNotFoundException $e ) {
			Log::error($e);
			return Response::json ( array (
					'error' => true,
					'thematiques' => null 
			), 200 );
		}
	}
	
	/**
	 * Cette fonction fait un appel à Nostra pour obtenir le json d'un record en détail
	 * Il le retourne tel quel ou fait une 500 ou 404 en cas de probleme.
	 *
	 * C'est géré en partie publique par du JS
	 *
	 * @param type $nostra_id
	 * @deprecated Cette méthode a été réécrite dans le DamusController
	 */
	public function getDemarche($nostra_id) {
		$ch = curl_init ( Config::get ( 'app.nostra_demarche_link' ) . $nostra_id );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		
		if (($json = curl_exec ( $ch )) === false) {
			/* Erreur de communication avec Nostra */
			curl_close ( $ch );
			return ("404");
			App::abort ( 404 );
		}
		curl_close ( $ch );
		
		if (is_string ( $json ) && is_object ( json_decode ( $json ) )) {
			$aJson = json_decode ( $json, true ); // true pour )--> tableau assoc au lieu d'un objet
			if (isset ( $aJson ['fiche'] [0] )) {
				return Response::json ( $aJson ['fiche'] [0] ); // seul cas ou on sort normalement de la fonction :-)
			}
			App::abort ( 404 );
		}
		
		App::abort ( 500 ); // si c'était pas du json valide ...
	}


	/**
	 * La fonction est appelée avec les ids des démarches en POST
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function postDemarcheLinks() {

		if ( Input::has('ids') ) {

			$ids = Input::get('ids');

			if (! is_array($ids)) { //si on a reçu une liste d'ids séparés par des virgules
				$ids = explode(',', $ids);
			}

			try {

				$arrayNostraPublics = new Illuminate\Database\Eloquent\Collection;
				$arrayNostraThematiquesADM = new Illuminate\Database\Eloquent\Collection;
				$arrayNostraThematiquesABC = new Illuminate\Database\Eloquent\Collection;
				$arrayNostraEvenements = new Illuminate\Database\Eloquent\Collection;

				foreach ($ids as $id) {

					$nostraDemarche = NostraDemarche::findOrFail($id);
					$arrayNostraPublics = $arrayNostraPublics->merge($nostraDemarche->nostraPublics);
					$arrayNostraThematiquesABC = $arrayNostraThematiquesABC->merge($nostraDemarche->nostraThematiquesabc);
					$arrayNostraThematiquesADM = $arrayNostraThematiquesADM->merge($nostraDemarche->nostraThematiquesadm);
					$arrayNostraEvenements = $arrayNostraEvenements->merge($nostraDemarche->nostraEvenements);
				}

				return Response::json(array(
					'error' => false,
					'publics' => $arrayNostraPublics->toArray(),
					'thematiquesabc' => $arrayNostraThematiquesABC->toArray(),
					'thematiquesadm' => $arrayNostraThematiquesADM->toArray(),
					'evenements' => $arrayNostraEvenements->toArray(),
				), 200);

			} catch (ModelNotFoundException $e) {
				Log::error($e);
				return Response::json(array(
					'error' => true,
					'publics' => [],
					'thematiquesabc' => [],
					'thematiquesadm' => [],
					'evenements' => [],
				), 200);
			}

		}

		// si on avait pas en POST le ou les ids des démarches, on retourne du vide
		return Response::json(array(
			'error' => true,
			'publics' => [],
			'thematiquesabc' => [],
			'thematiquesadm' => [],
			'evenements' => [],
		), 200);

	}

}
