<?php
class MonitorController extends Controller {
	
	/**
	 * Vue minimaliste : ne retourne que "ok" ou "ko" pour le système de monitoring du DTIC
	 * 
	 * @return View texte qui dit "ok" ou "ko"
	 */
	public function getStatus() {
		try {
			// TEST : Framework
			if (! $this->testFramework ()) {
				return ("ko");
			}
			
			// TEST : Test écriture des répertoires
			if (! $this->testDirectories ()) {
				return ("ko");
			}
			
			// TEST : DB
			if (! $this->testDatabase ()) {
				return ("ko");
			}
			
			// TEST : Présence de curl
			if (! $this->testCURL ()) {
				return ("ko");
			}
			
			// TEST : Présence de PHP-XML
			if (! $this->testXML ()) {
				return ("ko");
			}
			
			// TEST : Présence de PHP-mbstring
			
			// TEST : Présence de php-mcrypt
			
			// TEST : Import NOSTRA
			if (! $this->testNostra ()) {
				return ("ko");
			}
			
			// si on est arrivé jusque là ...
			return ("ok");
		} catch ( Exception $ex ) {
			Log::error($ex);
			return ("ko");
		}
	}
	
	/**
	 * Affiche la page de santé de Synapse
	 *
	 * @return View Structure JSON avec les tests/erreurs
	 */
	public function getHealth() {
		$response = array (
				'checkall' => true,
				'checks' => array () 
		);
		$onError = false;
		
		try {
			
			// TEST 1 : Framework
			if ($this->testFramework ()) {
				$response ['checks'] ['app'] ['result'] = "passed";
				$response ['checks'] ['app'] ['version'] = Config::get ( 'app.version' );
			} else {
				$response ['checks'] ['framework'] ['result'] = "failed";
				$onError = true;
			}
			
			// TEST : Test écriture des répertoires
			if ($this->testDirectories ()) {
				$response ['checks'] ['directories'] ['result'] = "passed";
				$response ['checks'] ['directories'] ['appPath'] = app_path ();
				$response ['checks'] ['directories'] ['basePath'] = base_path ();
				$response ['checks'] ['directories'] ['publicPath'] = public_path ();
				$response ['checks'] ['directories'] ['storagePath'] = storage_path ();
			} else {
				$response ['checks'] ['directories'] ['result'] = "failed";
				$onError = true;
			}
			
			// TEST : DB
			if ($this->testDatabase ()) {
				$response ['checks'] ['database'] ['result'] = "passed";
				$response ['checks'] ['database'] ['driver'] = Config::get ( "database.default" );
			} else {
				$response ['checks'] ['database'] ['result'] = "failed";
				$onError = true;
			}
			
			// TEST : Présence de curl
			if ($this->testCURL ()) {
				$response ['checks'] ['curl'] ['result'] = "passed";
				$response ['checks'] ['curl'] ['version'] = curl_version ();
			} else {
				$response ['checks'] ['curl'] ['result'] = "failed";
				$onError = true;
			}
			
			// TEST : Présence de XML
			if ($this->testXML ()) {
				$response ['checks'] ['xml'] ['result'] = "passed";
				$response ['checks'] ['xml'] ['version'] = "ok";
			} else {
				$response ['checks'] ['xml'] ['result'] = "failed";
				$onError = true;
			}
			
			// TEST : Import NOSTRA
			if ($this->testNostra ()) {
				$lastImportCarbon = $this->infoLastNostraImport ();
				$response ['checks'] ['nostra'] ['result'] = "passed";
				$response ['checks'] ['nostra'] ['lastImport'] = $lastImportCarbon->format ( 'Y-m-d H:i:s' );
				;
			} else {
				$lastImportCarbon = $this->infoLastNostraImport ();
				$response ['checks'] ['nostra'] ['result'] = "failed";
				$response ['checks'] ['nostra'] ['lastImport'] = $lastImportCarbon->format ( 'Y-m-d H:i:s' );
				;
				$onError = true;
			}
			
			return Response::json ( array (
					'error' => $onError,
					'results' => $response ['checks'] 
			), 200 );
		} catch ( Exception $ex ) {
			Log::error($ex);
			dd ( $ex );
			return Response::json ( array (
					'error' => true,
					'result' => $ex->getMessage () 
			), 200 );
		}
	}
	
	/**
	 * Soyons francs .
	 * .. si on est arrivé jusqu'ici c'est que le framework répond correctement hein ...
	 * 
	 * @return bool ok ou pas
	 */
	private function testFramework() {
		return true;
	}
	
	/**
	 * On teste les répertoires dans lesquels Synapse doit pouvoir écrire
	 * On ne teste pas le filesystem par defaut de laravel.
	 * Si il y avait eu une erreur, on n'aurait jamais du arriver ici
	 * Par contre on va tester les repertoires créés par nos soins.
	 * 
	 * @return bool ok ou pas *
	 */
	private function testDirectories() {
		$aDirectories = array (
				public_path () . '/temp/',
				storage_path() . '/uploads',
		);
		
		foreach ( $aDirectories as $path ) {
			if (! File::isWritable ( $path )) {
				return (false);
			}
		}
		
		return (true);
	}
	
	/**
	 * Test DB
	 * 
	 * @return bool ok ou pas
	 */
	private function testDatabase() {
		try {
			return false != DB::select ( 'SELECT 1' );
		} catch ( Exception $e ) {
			Log::error($e);
			return false;
		}
	}
	
	/**
	 * Test si la librairie PHP-CURL est installée
	 * 
	 * @return bool ok ou pas
	 */
	private function testCURL() {
		return function_exists ( 'curl_version' );
	}
	
	/**
	 * Test si la librairie PHP-XML est installée
	 * 
	 * @return bool ok ou pas
	 */
	private function testXML() {
		return class_exists ( 'XMLWriter' );
	}
	
	/**
	 * MLWriter
	 * Test de la fraicheur des import Nostra
	 * 
	 * @return bool ok ou pas
	 */
	private function testNostra() {
		$lastUpdate = DB::table ( 'nostra_publics' )->orderBy ( 'nostra_state', 'desc' )->first ();
		
		if (Carbon::parse ( $lastUpdate->nostra_state )->diffInDays () > 2) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Obtenir la dernière mise à jour de Nostra
	 * 
	 * @return Carbon Date de la dernière mise à jour Nostra
	 */
	private function infoLastNostraImport() {
		return (Carbon::parse ( DB::table ( 'nostra_publics' )->orderBy ( 'nostra_state', 'desc' )->first ()->nostra_state ));
	}
}
