<?php
/**
 * 
 * Nomenclature : une fonction commencant par un underscore "_" est une fonction récursive
 * 
 */

use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\LogicException;
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class importFromNostraV2 extends Command {
	
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cron:importFromNostraV2';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importation de Nostra V2 dans DAMUS';
	
	/**
	 * ID des démarches présentes dans les flux Nostra
	 * 
	 * @var array
	 */
	private $demarchesProcessed=[];
	
	/**
	 * Date de début de la synchro
	 * 
	 * @var \DateTime
	 */
	private $started;
	
	/**
	 * 
	 * @var GuzzleHttp\Client $client
	 */
	private $client;
	
	/**
	 * Liste d'exceptions qui se sont produites durant l'import
	 * @var array
	 */
	private $exceptions;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
		$this->started = new \DateTime();
		$this->exceptions=[];
	}
	
	/**
	 * Execute the console command.
	 * 
	 * La logique de l'import est la suivante 
	 * 
	 * 1.	Importer les publics cibles et leurs enfants
	 *		Un public peut avoir des enfants --> récursivité
	 * 
	 * 2.	Importer les thématiques ABC et leurs enfants
	 *		Une thématique peut avoir des enfants --> récursivité
	 *		
	 * 3.	On fait le lien entre publics cibles et thematiques ABC, en passant les publics cibles un par un.
	 *		C'est quasi la même manip que le point 2, mais dans le modèle Nostra il n'y a pas de lien entre publics et thematiques ABC.
	 *		Donc, si une thématique n'a pas été liée à un public, on ne l'aurait jamais. 
	 *		Par sécurité on fait donc deux fois cet import.
	 *		Lorsqu'une thématique est liée à un public cible, on la lie automatique à son parent.
	 *		Mais l'inverse est faux! On ne lie pas une thématiques avec les publics enfants.
	 *		Exemple :	"Pouvoir locaux" à un enfant "CPAS". Si une thématique est liée à "pouvoir locaux", elle ne l'est pas forcément à "CPAS".
	 *					Mais si une thématique est liées à "CPAS" elle est automatiquement liée à "pouvoir locaux"
	 * 
	 * 4.	Import des événements déclencheurs, en passant un public et un evenement à chaque fois
	 *		Encore une fois, on répare un éventuel manquement de Nostra en liant un événement et un public à ses publics parents.
	 *		Et a ses éventuels thématiques parentes.
	 * 
	 * 5.	Import des thématiques ADM
	 *		Une thématique peut avoir des enfants --> récursivité
	 *		Une thématique n'a pas de lien avec des publics, des thématiques abc ou des événement. Pas de lien à créer donc.
	 * 
	 * 6.	On procède à l'import des démarches par publics.
	 *		Comme une démarche est OBLIGATOIREMENT liée à un public, on les aura toutes.
	 *
	 * 6bis On soft-delete les démarches absentes du flux
	 * 
	 * 7.	On appelle le détail des fiches et on en profitera pour :
	 *		- Lier aux publics, abc, adm et evenements,
	 *		- Importer formulaires et documents
	 * 
	 * REGLES :
	 * - Si un élément est supprimé dans Nostra, on le conserve
	 * - Si un élément est mis à jour dans Nostra, on le met à jour : les infos de Nostra sont prioritaires sur celles de Synapse
	 *
	 *
	 * @return mixed
	 */
	public function fire() {
		Log::info ( "Demarrage de l'import Nostra" );
		$this->client=new GuzzleHttp\Client();
		DB::beginTransaction();
		try {
			/*// 1. Import des publics cibles
			Log::info ( "----- Import des publics cibles -----" );
			$totalPublicsCibles = $this->_importPublics();
			Log::info ( "$totalPublicsCibles public importes" );
			
			// 2. Import des thématique ABC
			Log::info ( "----- Import des thematiques ABC -----" );
			$totalThematiquesABC = $this->_importThematiquesABC();
			Log::info ( "$totalThematiquesABC thematiques ABC importees" );
			
			// 3. Lien entre les publics et les thématiques ABC
			Log::info ( "----- Lien entre publics et thematiques ABC -----" );
			$arrayNostraPublics = NostraPublic::all ();
			foreach ( $arrayNostraPublics as $nostraPublic ) {
				$total = $this->_linkThematiquesABCWithPublic ( $nostraPublic );
				Log::info(" + ".$nostraPublic->title . " [$total thematiques ABC] ");
			}
			
			//4. Import des événements déclencheurs
			Log::info ( "----- Import des événements déclencheurs -----" );
			$arrayNostraPublics = NostraPublic::all ();
			foreach ( $arrayNostraPublics as $nostraPublic ) {
				$arrayNostraThematiquesABC = $nostraPublic->nostraThematiquesabc ()->get ();
				Log::info(" + " . $nostraPublic->title);
				foreach ( $arrayNostraThematiquesABC as $nostraThematiqueABC ) {
					$total = $this->importEvenement ( $nostraPublic, $nostraThematiqueABC );
					Log::info("    + ".$nostraThematiqueABC->title . " [$total evenements] ");
				}
			}
			
			//5. Import des thématique ADM
			Log::info ( "----- Import des thematiques ADM -----" );
			$totalThematiquesADM = $this->_importThematiquesADM();
			Log::info ( "$totalThematiquesADM thematiques ADM importees" );*/
			
			//6. Importation des démarches
			Log::info ( "----- Import des demarches -----" );
			$arrayNostraPublics = NostraPublic::all ();
			foreach ( $arrayNostraPublics as $nostraPublic ) {
				$count = $this->importDemarches ( $nostraPublic );
				Log::info ( "  + " . $nostraPublic->title . " [$count demarches liees]" );
			}
			
			// 6bis. Soft-delete des demarches absentes du flux
			Log::info ( "----- Soft-delete des demarches absentes du flux -----" );
			$count = $this->deleteDemarches();
			Log::info ( "{$count} demarches soft-deletees" );
			
			//7. Importation du détail des démarches
			Log::info ( "----- Détails des demarches -----" );
			$arrayNostraDemarches = NostraDemarche::whereIn('nostra_id', $this->demarchesProcessed)->get();
			Log::info ( $arrayNostraDemarches->count().' démarches concernées' );
			foreach ( $arrayNostraDemarches as $demarche ) {
				try {
					$this->importDemarcheDetail($demarche);
					
				}
				catch(Exception $e) {
					$this->addException($e, "Une erreur s'est produite à l'import de la démarche {$demarche->id}, elle a été ignorée");
				}
			}
			
			// Fin de l'import ... tout c'est bien passé --> on commit
			DB::commit();
		}
		catch (Exception $e) {
			DB::rollBack();
			$this->addException($e, "Une erreur s'est produite, rollback des opérations DB");
		}
		
		if($this->exceptions){
			$this->reportExceptions();
		}
		Log::info ( "Fin de l'import Nostra");
	}
	
	/**
	 * Récolte les exceptions se produisant lors de l'import
	 * 
	 * @param Exception $e
	 * @param string $msg
	 */
	private function addException(Exception $e, $msg) {
		Log::error($msg);
		Log::error($e);
		$this->exceptions[]=[
			'msg'=>$msg,
			'e'=>$e
		];
	}
	
	/**
	 * Logge et envoi par email les erreurs s'étant produites lors de l'import
	 * 
	 */
	private function reportExceptions() {
		Mail::queueOn('nostra', 'emails.nostra.import', ['exceptions'=>$this->exceptions], function(\Illuminate\Mail\Message $message) {
			$message->to(Config::get('app.rta'))->subject(Lang::get('admin/nostra/messages.import.mail.subject'));
		});
	}
	
	/**
	 * Effectue une requête GET à l'url spécifiée et rend le json retourné
	 * 
	 * @param string $url Url vers laquelle la requête sera effectuée
	 * @param string $rootexpected Elément parent attendu
	 * @return mixed
	 */
	private function getJson($url, $rootexpected) {
		$response=$this->client->get($url);
		$json=$response->json();
		if($json['status']=='Error') {
			throw new UnexpectedValueException("Error calling {$url} : ".$json['ErrorMessage']);
		}
		elseif($json['status']=='No result') {
			$json[$rootexpected]=[]; // Ajouter l'élément attendu, vide, afin de ne pas devoir tenir compte de son existence après
		}
		elseif(!isset($json[$rootexpected])) {
			Log::warning("Root element {$rootexpected} expected in result of {$url}"); // TODO : Peut-être faudrait-il prendre une action plus drastique qu'un simple warning ?
			$json[$rootexpected]=[];
		}
		return $json;
	}
	
	/**
	 * Import des publics cibles
	 * 
	 * Fonction récursive
	 * @param int $level
	 * @param NostraPublic $parent
	 * @return int
	 */
	private function _importPublics($level = 0, NostraPublic $parent = null) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException("Une récursivité de >64 a été atteinte dans _importPublics");
		}
		$count = 0;
		if ($parent) { // Si on a un parent, on appele le WS categories_children
			$json=$this->getJson(str_replace ( "{{publicId}}", $parent->nostra_id, Config::get ( 'app.nostraV2_publicsChildren' )), 'categories');
		}
		else { // Si pas de parent, on appelle le WS categories_root 
			$json=$this->getJson(Config::get ( 'app.nostraV2_publicsRoot' ), 'categories');
		}
		foreach ( $json['categories'] as $element ) {
			if (! isset ( $element['id'] ) && isset ( $element['tid'] )) { // petit kludge pour corriger une erreur de nostra : le WS root retourne "id" et le WS children retourne "tid" ... yeah!
				$element['id'] = $element['tid'];
			}
			// on crée un objet NostraPublic (ou on charge un existant)
			$date = new \DateTime ();
			$object = NostraPublic::firstOrNew ( array (
					"nostra_id" => $element['id']
			) );
			$object->nostra_id = $element['id'];
			if($parent) {
				$object->parent_id = $parent->id;
			}
			$object->title = HTML::decode ( $element['title'] );
			$object->nostra_state = $date;
			$object->save ();
			// on ajoute cet élément au compte
			$count ++;
			Log::info(str_repeat("  ", $level) . " + " . $object->title);
			// on recherche des éventuels enfants
			$count += $this->_importPublics ( $level + 1, $object );
		}
		return $count;
	}
	
	
	/**
	 * Import des thématiques ABC
	 * 
	 * Fonction récursive
	 * @param int $level
	 * @param NostraThematiqueabc $parent
	 * @return int
	 */
	private function _importThematiquesABC($level = 0, NostraThematiqueabc $parent = null) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException( "Une récursivité de >64 a été atteinte dans _importThematiquesABC" );
		}
		$count = 0;
		if ($parent) { // Si on a un parent, on appele le WS categories_children
			$json=$this->getJson(str_replace ( "{{thematiqueABCId}}", $parent->nostra_id, Config::get ( 'app.nostraV2_thematiquesABCChildren' )), 'categories');
		}
		else { // Si pas de parent, on appelle le WS categories_root
			$json=$this->getJson(Config::get ( 'app.nostraV2_thematiquesABCRoot' ), 'categories');
		}
		foreach ( $json['categories'] as $element ) {
			if (! isset ( $element['id'] ) && isset ( $element['tid'] )) { // petit kludge pour corriger une erreur de nostra : un WS retourne "id" et l'autre "tid" ... yeah!
				$element['id'] = $element['tid'];
			}
			// on crée un objet NostraThematiqueabc
			$date = new \DateTime ();
			$object = NostraThematiqueabc::firstOrNew ( array (
				"nostra_id" => $element['id'] 
			) );
			$object->nostra_id = $element['id'];
			if($parent) {
				$object->parent_id = $parent->id;
			}
			$object->title = HTML::decode ( $element['title'] );
			$object->nostra_state = $date;
			$object->save ();
			// on ajoute cet élément au compte
			$count ++;
			 Log::info(str_repeat("  ", $level) . " + " . $object->title);
			// on recherche des éventuels enfants
			$count += $this->_importThematiquesABC ( $level + 1, $object );
		}
		return $count;
	}
	
		 
	/**
	 * Appel au WS de Nostra pour lier les thématiques ABC avec les publics
	 * 
	 * Le principe est le suivant :
	 * On boucle sur les publics.
	 * A chaque public, on appelle le WS categories_available pour lister les thématiques ABC liées.
	 * A chaque thématique trouvée, on appelle le WS pour trouver les enfants de cette thématique et les lier également au public.
	 * La fonction est appelée de facon récursive.
	 * 
	 * @param NostraPublic $nostraPublic
	 * @param int $level niveau d'appel récursif
	 * @param NostraThematiqueabc $parent id du parent
	 * @return int
	 */
	private function _linkThematiquesABCWithPublic(NostraPublic $nostraPublic, $level = 0, NostraThematiqueabc $parent = null) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException( "Une récursivité de >64 a été atteinte dans linkThematiquesABCWithPublic" );
		}
		$count = 0;
		if ($parent ) { // Si on aun parent, on appele le WS categories_children
			$json=$this->getJson(str_replace ( "{{thematiqueABCId}}", $parent->nostra_id, Config::get ( 'app.nostraV2_thematiquesABCChildren' )), 'categories');
		}
		else { // Si pas de parent (on est dans une thématique root), on appelle le WS categories_available 
			$json=$this->getJson(str_replace ( "{{publicId}}", $nostraPublic->nostra_id, Config::get ( 'app.nostraV2_thematiquesABC' )), 'categories');
		}
		foreach ( $json['categories'] as $element ) {
			if (! isset ( $element['id'] ) && isset ( $element['tid'] )) { // petit kludge pour corriger une erreur de nostra : un WS retourne "id" et l'autre "tid" ... yeah!
				$element['id'] = $element['tid'];
			}
			// on crée un objet NostraThematiqueABC
			$date = new \DateTime ();
			$object = NostraThematiqueabc::firstOrNew ( array (
					"nostra_id" => $element['id']
			) );
			$object->nostra_id = $element['id'];
			if($parent) {
				$object->parent_id=$parent->id;
			}
			$object->title = HTML::decode ( $element['title'] );
			$object->nostra_state = $date;
			$object->save ();
			// on ajoute cet élément au compte
			$count ++;
			// on attache ceci aux public et à ses parents
			$this->_linkNostraThematiqueToNostraPublicTree ( $object, $nostraPublic );
			// on recherche des éventuels enfants --> appel récusrsif à cette fonction
			$count += $this->_linkThematiquesABCWithPublic ( $nostraPublic, $level + 1, $object );
		}
		return $count;
	}
	
	
	/**
	 * Cette fonction est appeleée depuis linkThematiquesABCWithPublic pour lier une thématique ABC à un public et à tous ses parents
	 * 
	 * @param NostraThematiqueabc $thematique
	 * @param NostraPublic $public
	 * @param int $level
	 * @return int
	 */
	private function _linkNostraThematiqueToNostraPublicTree(NostraThematiqueabc $thematique, NostraPublic $public, $level = 0) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException ( "Une récursivité de >64 a été atteinte dans linkNostraThematiqueToNostraPublicTree" );
		}
		if (! $thematique->nostraPublics->contains ( $public->id )) {
			$thematique->nostraPublics ()->attach ( $public );
		}
		
		//Lien avec le parent éventuel
		$dad = $public->ancestor;
		if ($dad instanceof NostraPublic) {
			$this->_linkNostraThematiqueToNostraPublicTree ( $thematique, $dad, $level + 1 );
		}
		return 0;
	}
	
	
	/**
	 * Importation des événements déclencheurs
	 * @param NostraPublic $public
	 * @param NostraThematiqueabc $thematique
	 * @return int
	 */
	private function importEvenement(NostraPublic $public, NostraThematiqueabc $thematique) {
		$count = 0;
		$ws = Config::get ( 'app.nostraV2_evenementsDeclencheurs' );
		$ws = str_replace ( '{{publicId}}', $public->nostra_id, $ws );
		$ws = str_replace ( '{{thematiqueABCId}}', $thematique->nostra_id, $ws );
		$json=$this->getJson($ws, 'events');
		foreach ( $json['events'] as $element ) {
			// on crée un objet NostraEvenement
			$date = new \DateTime ();
			$object = NostraEvenement::firstOrNew ( array (
					"nostra_id" => $element['nid'] 
			) );
			$object->nostra_id = $element['nid'] ;
			$object->title = HTML::decode ( $element['title'] );
			$object->nostra_state = $date;
			$object->save ();
			// on ajoute cet élément au compte
			$count ++;
			// on lie au public et à ses enfants
			$this->_linkNostraEvenementToNostraPublicTree ( $object, $public );
			// on le lie à la thématique et à ses enfants
			$this->_linkNostraEvenementToNostraThematiqueABCTree ( $object, $thematique );
		}
		return $count;
	}
	
	
	/**
	 * Cettte fonction est appeleée depuis importEvenements pour lier un événement ABC à un public et à tous ses enfants
	 * 
	 * @param NostraEvenement $evenement
	 * @param NostraPublic $public
	 * @param int $level
	 * @return int
	 */
	private function _linkNostraEvenementToNostraPublicTree(NostraEvenement $evenement, NostraPublic $public, $level = 0) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException ("Une récursivité de >64 a été atteinte dans linkNostraEvenementToNostraPublicTree" );
		}
		
		if (! $evenement->nostraPublics->contains ( $public->id )) {
			$evenement->nostraPublics ()->attach ( $public );
		}
		
		$dad = $public->ancestor;
		if ($dad instanceof NostraPublic) {
			$this->_linkNostraEvenementToNostraPublicTree ( $evenement, $dad, $level + 1 );
		}
		
		return 0;
	}
	
	
	/**
	 * 
	 * @param NostraEvenement $evenement
	 * @param NostraThematiqueabc $thematique
	 * @param int $level
	 * @return int
	 */
	private function _linkNostraEvenementToNostraThematiqueABCTree(NostraEvenement $evenement, NostraThematiqueabc $thematique, $level = 0) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException ( "Une récursivité de >64 a été atteinte dans linkNostraEvenementToNostraThematiqueABCTree" );
		}
		
		if (! $evenement->nostraThematiquesabc->contains ( $thematique->id )) {
			$evenement->nostraThematiquesabc ()->attach ( $thematique );
		}
		$dad = $thematique->ancestor;
		if ($dad instanceof NostraThematiqueabc) {
			$this->_linkNostraEvenementToNostraThematiqueABCTree ( $evenement, $dad, $level + 1 );
		}
		
		return 0;
	}
	
	
	/**
	 * Appel au webservice de nostra pour importer les thematiques administratives
	 * Cette fonction peut être appelée de façon récursive car des thematiques peuvent avoir des thematiques enfants
	 *
	 * @param int $level niveau d'appel récursif
	 * @param NostraThematiqueadm $parent id du parent
	 * @return type
	 */
	private function _importThematiquesADM($level = 0, NostraThematiqueadm $parent = null) {
		if ($level > 64) { // Sécurité contre les boucles infinies
			throw new LogicException ("Une récursivité de >64 a été atteinte dans importThematiquesADM" );
		}
		$count = 0;
		if ($parent) { // Si on a un parent, on appele le WS categories_children
			$json=$this->getJson(str_replace ( "{{thematiqueADMId}}", $parent->nostra_id, Config::get ( 'app.nostraV2_thematiquesADMChildren' )), 'categories');
		}
		else { // Si pas de parent, on appelle le WS categories_root
			$json=$this->getJson(Config::get ( 'app.nostraV2_thematiquesADMRoot' ), 'categories');
		}
		foreach ( $json['categories'] as $element ) {
			if (! isset ( $element['id'] ) && isset ( $element['tid'] )) { // petit kludge pour corriger une erreur de nostra : un WS retourne "id" et l'autre "tid" ... yeah!
				$element['id'] = $element['tid'];
			}
			// on crée un objet NostraThematiqueadm
			$date = new \DateTime ();
			$object = NostraThematiqueadm::firstOrNew ( array (
				"nostra_id" => $element['id'] 
			));
			$object->nostra_id = $element['id'];
			if($parent) {
				$object->parent_id = $parent->id;
			}
			$object->title = HTML::decode ( $element['title']);
			$object->nostra_state = $date;
			$object->save ();
			// on ajoute cet élément au compte
			$count ++;
			Log::info(str_repeat("  ", $level) . " + " . $object->title);
			// on recherche des éventuels enfants
			$count += $this->_importThematiquesADM ( $level + 1, $object );
		}
		return $count;
	}
	
	
	/**
	 * Import des démarches, par appel au records_for_categories en pasant le public
	 * @param NostraPublic $public
	 * @return int
	 */
	private function importDemarches(NostraPublic $public) {
		$count = 0;
		$ws = Config::get ( 'app.nostraV2_demarchesFromPublic' );
		$ws = str_replace ( '{{publicId}}', $public->nostra_id, $ws );
		$json=$this->getJson($ws, 'fiche');
		foreach ( $json['fiche'] as $fiche ) {
			
			// Ne traiter qu'une fois la même démarche
			if(in_array($fiche['nid'], $this->demarchesProcessed)) {
				continue;
			}
			try {
				$this->demarchesProcessed[]=$fiche['nid'];
				
				$date = new \DateTime ();
				$oDemarche = NostraDemarche::withTrashed()->where('nostra_id', '=', $fiche['nid'])->first();
				if(!$oDemarche) $oDemarche=new NostraDemarche();
				$oDemarche->nostra_id = $fiche['nid'];
				$oDemarche->title = HTML::decode ( $fiche['node_title'] );
				$oDemarche->title_long = isset ( $fiche['title_user_long'] ) ? HTML::decode ( $fiche['title_user_long']) : '';
				$oDemarche->title_short = isset ( $fiche['title_user_short'] ) ? HTML::decode ( $fiche['title_user_short']) : '';
				if (isset ( $fiche['right_obligation'])) {
					switch (strtolower ( $fiche['right_obligation'])) {
						case 'droit' :
							$oDemarche->type = 'droit';
							break;
						case 'obligation' :
							$oDemarche->type = 'obligation';
							break;
						default :
							$oDemarche->type = 'aucun';
							break;
					}
				} else {
					$oDemarche->type = 'aucun';
				}
				$oDemarche->simplified = isset ( $fiche['simplified']) ? ((strtolower ( $fiche['simplified']) == 'oui') ? 1 : 0) : 0;
				$oDemarche->german_version = isset ( $fiche['german_version']) ? ((strtolower ( $fiche['german_version']) == 'oui') ? 1 : 0) : 0;
				$oDemarche->nostra_state = $date;
				
				// Restaurer la fiche si elle était supprimée (implicitement c'est donc qu'elle existe)
				if($oDemarche->deleted_at) {
					// Note : En théorie on pourrait se contenter de onlyTrashed(), mais sécurité au cas où le deleted_at n'aurait pas été positionné sur la démarche
					if($synapseDemarche=$oDemarche->demarche()->getQuery()->withTrashed()->first()) {
						$synapseDemarche->restore();
					}
					$oDemarche->deleted_at=null;
				}
				$oDemarche->save ();
				$count ++; // on ajoute cet élément au compte
			}
			catch(Exception $e) {
				$this->addException($e, "Une erreur s'est produite à l'import du détail de la démarche {$demarche->id}, elle a été ignorée");
			}
		}
		return $count; 
	}
	
	/**
	 * Soft-deleter les démarches nostra (et démarches éventuellement associées) qui n'ont pas été modifiées par la synchro
	 * 
	 * (Si absentes du flux, c'est qu'elles ne sont plus associées à synapse => On les soft-delete pour les garder en désactivé)
	 * @return int Nombre de démarches qui ont été supprimées
	 */
	private function deleteDemarches() {
		$count=0;
		foreach(NostraDemarche::where('updated_at', '<', $this->started)->get() as $oDemarche) {/* @var NostraDemarche $oDemarche */
			if($synapseDemarche=$oDemarche->demarche()->getResults()) {
				$synapseDemarche->delete();
			}
			$oDemarche->delete();
			$count++;
		}
		return $count;
	}
	
	/**
	 * Import du détail de la démarche
	 * 
	 * @param NostraDemarche $demarche
	 */
	private function importDemarcheDetail(NostraDemarche $demarche) {
		$ws = Config::get ( 'app.nostraV2_demarcheDetail' );
		$ws = str_replace ( '{{demarcheId}}', $demarche->nostra_id, $ws );
		$json=$this->getJson($ws, 'fiche');
		$fiche=$json['fiche'][0]; //le retour se fait sous forme d'un tableau ... même si il y a un seul 
		
		// on crée un objet NostraDemarche
		$date = new \DateTime ();
		$oDemarche = NostraDemarche::firstOrNew ( array (
			"nostra_id" => $fiche['nid'] 
		));
		
		// On attache aux événements, en épluchant le tableau events en retour
		// Mais on détache d'abord tout
		$eventsIds = [];
		if (isset($fiche['events'])) {
			foreach ($fiche['events'] as $event) {
				$evt = NostraEvenement::where("nostra_id" ,"=", $event['id'])->first();
				if ($evt->id > 0) {
					array_push($eventsIds, $evt->id);
				}
			}
			$oDemarche->nostraEvenements()->sync($eventsIds);
		}
		
		// On parcour les catégorie pour lier aux publics, aux thématiques ABC et aux thématiques ADM
		$thematiquesABCIds = [];
		$thematiquesADMIds = [];
		$publicsIds = [];
		if (isset($fiche['categories'])) {
			foreach ($fiche['categories'] as $categorie) {
				switch ($categorie['type']) {
					// Thématique ADM
					case 'policy':
						$theme = NostraThematiqueadm::where("nostra_id", "=", $categorie['id'])->first();
						if ($theme->id > 0) {
							array_push($thematiquesADMIds, $theme->id);
						}
						break;
					// Thématique ABC
					case 'abc_thematic':
						$theme = NostraThematiqueabc::where("nostra_id", "=", $categorie['id'])->first();
						if ($theme->id > 0) {
							array_push($thematiquesABCIds, $theme->id);
						}
						break;
					// Public cible
					case 'target':
						$public = NostraPublic::where("nostra_id", "=", $categorie['id'])->first();
						if ($public->id > 0) {
							array_push($publicsIds, $public->id);
						}
						break;
					default:
						break;
				}
			}
		}
		
		$oDemarche->nostraPublics()->sync($publicsIds);
		$oDemarche->nostraThematiquesabc()->sync($thematiquesABCIds);
		$oDemarche->nostraThematiquesadm()->sync($thematiquesADMIds);
		
		// on regarde si il existe des formulaires
		if (isset ( $fiche['forms'] )) {
			foreach ( $fiche['forms'] as $form ) {
				$oForm = NostraForm::firstOrNew ( array (
						"nostra_id" => $form['id'] 
				) );
				$oForm->nostra_id = $form['id'];
				$oForm->title = HTML::decode ( $form['title'] );
				$oForm->form_id = isset ( $form['form_id'] ) ? $form['form_id'] : "";
				$oForm->language = isset ( $form['language'] ) ? $form['language'] : "";
				$oForm->url = isset ( $form['url'] ) ? $form['url'] : "";
				$oForm->smart = isset ( $form['smart'] ) ? ((strtolower ( $form['smart'] ) == 'oui') ? 1 : 0) : 0;
				$oForm->priority = isset ( $form['priority'] ) ? $form['priority'] : "";
				$oForm->esign = isset ( $form['e_signature'] ) ? ((strtolower ( $form['e_signature'] ) == 'oui') ? 1 : 0) : 0;
				$oForm->format = isset ( $form['format'] ) ? $form['format'] : "";
				$oForm->simplified = isset ( $form['simplified'] ) ? ((strtolower ( $form['simplified'] ) == 'oui') ? 1 : 0) : 0;
				$oForm->nostra_state = $date;
				$oForm->save ();
				if ($oForm->nostraDemarches->contains ( $oDemarche->id )) {
					$oForm->nostraDemarches ()->detach ( $oDemarche );
				}
				
				
				$nostra_parent=form['parent_id'];
				$parent=null;
				if($nostra_parent) {
					Log::info("Evénement avec un parent : D{$oDemarche->id} F{$oForm->nostra_id}");
					$parent=NostraForm::first(['nostra_id' => $nostra_parent]);
					if(!$parent) {
						// Il faut alors créer le parent avec juste son ID
						$parent=new NostraForm();
						$parent->nostra_id = $nostra_parent;
						$parent->save(); // TODO : Vérifier qu'on peut sauver avec juste ce champ rempli !
					}
					
					// FIXME : Cela donne une erreur, mais pas encore compris pourquoi.
					// TODO : Une fois trouvé, vérifier que le champ nostra_form_parent_id est bien initialisé
					$oForm->nostraDemarches ()->attach ( [$oDemarche->id => ['nostra_form_parent_id'=>$parent->id]]);
				}
				else {
					$oForm->nostraDemarches ()->attach ( $oDemarche );
				}
				
			}
		}
		
		// on regarde si il existe des documents
		if (isset ( $fiche['documents'] )) {
			foreach ( $fiche['documents'] as $document ) {
				$oDocument = NostraDocument::firstOrNew ( array (
						"nostra_id" => $document['id'] 
				) );
				$oDocument->nostra_id = $document['id'];
				$oDocument->title = HTML::decode ( $document['title'] );
				$oDocument->document_id = isset ( $document['document_id'] ) ? $document['document_id'] : "";
				$oDocument->language = isset ( $document['language'] ) ? $document['language'] : "";
				$oDocument->url = isset ( $document['url'] ) ? $document['url'] : "";
				$oDocument->nostra_state = $date;
				$oDocument->save ();
				if (! $oDocument->nostraDemarches->contains ( $oDemarche->id )) {
					$oDocument->nostraDemarches ()->attach ( $oDemarche );
				}
			}
		}
		$oDemarche->save ();
	}
}
