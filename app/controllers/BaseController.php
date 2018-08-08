<?php
abstract class BaseController extends Controller {
	
	/**
	 * The currently authenticated user.
	 *
	 * @var \User
	 */
	private $loggedUser;
	const SESSION_RETURNTO_ROUTENAME = 'baseCtrl_returnTo_routeName'; //nom de la variable de session utilisée par les fonctionnalités de returnTo;
	
	private $section;
	
	/**
	 * Initialisation
	 *
	 * @return \BaseController
	 */
	public function __construct() {
		$this->beforeFilter ( 'csrf', array ('on' => 'post'));
		
		// Partager la variable user
		$this->loggedUser = Auth::user ();
		View::share('loggedUser', $this->loggedUser);
		
		$this->section=$this->getSection();
		View::share('sectionIcon', $this->getSectionIcon($this->section));
		
		// Traiter le cas spécifique de la corbeille : La section est préfixée de '-trash' dans ce cas.
		$uri=Route::getCurrentRoute()->getUri();
		if(ends_with($uri, ['trash','datatrash','restore']))
			$this->section.='-trash';
		
		// Charger le menu sidebar
		$this->buildSidebarMenu();
	}
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes publiques mappées avec les routes, que les sous-classes doivent redéfinir
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Méthode correspondant à la page d'index du contrôleur courant
	 */
	public abstract function getIndex();
	
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes supplémentaires que les sous-classes doivent redéfinir
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Route correspondant à la page d'index du contrôleur courant
	 */
	protected abstract function routeGetIndex();
	
	/**
	 * Section courante
	 *
	 * @return string
	 */
	protected abstract function getSection();
	
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes utilitaires, donc finales et accessibles par les sous-classes
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	
	/**
	 * Rediriger vers la page d'index du modèle courant avec un msg d'erreur
	 *
	 * @return \User
	 */
	protected final function getLoggedUser(){
		return $this->loggedUser;
	}
	
	/**
	 * Rediriger vers la page d'index du modèle courant avec un msg d'erreur
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected final function redirectNoRight($url=null) {
		if(!$url)
			$url=(!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : $this->routeGetIndex();
		return Redirect::secure($url)->with ( 'error', Lang::get ( 'general.no_right' ));
	}
	
	/**
	 * Renvoyer une notification d'erreur sous forme de servermodal
	 * 
	 * @return \Illuminate\View\View
	 */
	protected final function serverModalNoRight() {
		return View::make('notifications', ['error'=>Lang::get('general.no_right_action')]);
	}
	
	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected final function setupLayout() {
		if (! is_null ( $this->layout )) {
			$this->layout = View::make ( $this->layout );
		}
	}
	
	
	/**
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 * Méthodes à usage interne de la classe
	 * ----------------------------------------------------------------------------------------------------------------------------------------------
	 */
	
	/**
	 * Associe une icône font-awesome à une section
	 *
	 * @param string $section
	 * @return string
	 */
	protected final function getSectionIcon($section){
		switch($section) {
			case 'contact'            :return 'envelope';
			case 'dashboard'          :return 'area-chart';
			case 'ideas'              :return 'lightbulb-o';
			case 'demarches'          :return 'briefcase';
			case 'ewbsactions'        :return 'magic';
				
			case 'formslibrary'       :
			case 'annexes'            :
			case 'eforms'             :return 'wpforms';
				
			case 'components'         :
			case 'pieces'             :
			case 'tasks'              :return 'clipboard';
				
			case 'damus'              :return 'connectdevelop';
				
			case 'taxonomy'           :
			case 'taxonomycategories' :
			case 'taxonomysynonyms'   :
			case 'taxonomytags'       :return 'tag';
				
			case 'ewbsservices'       :return 'wrench';
				
			case 'admin'              :
			case 'administrations'    :
			case 'ewbsmembers'        :
			case 'jobs'               :
			case 'failedjobs'         :
			case 'roles'              :
			case 'users'              :return 'cog';
				
			case 'trash'              :return 'trash-o';
			default                   :return '';
		}
	}
	
	/**
	 * Construire le menu affiché dans la sidebar
	 *
	 * Place dans la variable partagée "sidebarMenu" un array
	 * reprenant tous les points de menu à afficher selon l'utilisateur connecté.
	 */
	private final function buildSidebarMenu() {
	
		// Pas de menu si pas loggé
		if(!$this->loggedUser) {
			View::share('sidebarMenu', null);
			return;
		}
	
		$menu=[
			[ // Dashboard
				'label'      => Lang::get ( 'admin/dashboard/messages.menu' ),
				'section'    => 'dashboard',
				'route'      => 'adminDashboardGetIndex',
			],
			[ // Projets de simplif
				'label'      => Lang::get ( 'admin/ideas/messages.menu' ),
				'section'    => 'ideas',
				'route'      => 'ideasGetIndex',
				'permission' => 'ideas_display',
			],
			[ // Démarches
				'label'      => Lang::get ( 'admin/demarches/messages.menu' ),
				'section'    => 'demarches',
				'route'      => 'demarchesGetIndex',
				'permission' => 'demarches_display',
			],
			[ // Actions
				'label'      => Lang::get ( 'admin/ewbsactions/messages.menu' ),
				'section'    => 'ewbsactions',
				'route'      => 'ewbsactionsGetIndex',
				'permission' => 'ewbsactions_display',
			],
			[ // Formulaires
				'label'     => Lang::get ( 'admin/eforms/messages.supermenu' ),
				'section'   => 'formslibrary',
				'permission'=> 'formslibrary_display',
				'route'     => 'eformsGetIndex',
				/*'submenu'   => [
					[
						'label'     => Lang::get ( 'admin/annexes/messages.menu' ),
						'section'   => 'annexes',
						'route'     => 'annexesGetIndex',
					],[
						'label'     => Lang::get ( 'admin/eforms/messages.menu' ),
						'section'   => 'eforms',
						'route'     => 'eformsGetIndex',
					]
				],*/
			],
			[ // Pièces et données
				'label'      => Lang::get ( 'admin/pieces/messages.supermenu' ),
				'section'    => 'components',
				'permission' => 'pieces_tasks_display',
				'submenu'    => [
					[
						'label'      => Lang::get ( 'admin/pieces/messages.menu' ),
						'section'    => 'pieces',
						'route'      => 'piecesGetIndex',
					],[
						'label'      => Lang::get ( 'admin/tasks/messages.menu' ),
						'section'    => 'tasks',
						'route'      => 'tasksGetIndex',
					],/*[
						'label'      => Lang::get ( 'admin/piecesrates/messages.menu' ),
						'route'      => 'piecesratesGetIndex',
					],*//*[
						'label'      => Lang::get ( 'admin/piecestypes/messages.menu' ),
						'route'      => 'piecestypesGetIndex',
					],*/
				],
			],
			[ // Damus
				'label'      => Lang::get ( 'admin/damus/messages.menu' ),
				'section'    => 'damus',
				'route'      => 'damusGetIndex',
				'permission' => 'damus_manage',
			],
			[ // Taxonomie
				'label'      => Lang::get ('admin/taxonomy/messages.menu' ),
				'section'    => 'taxonomy',
				'permission' => 'taxonomy_display',
				'submenu'	 => [
					[
						'label'      => Lang::get( 'admin/taxonomy/messages.menu-categories' ),
						'section'    => 'taxonomycategories',
						'route'      => 'taxonomycategoriesGetIndex',
					],
					[
						'label'      => Lang::get( 'admin/taxonomy/messages.menu-tags' ),
						'section'    => 'taxonomytags',
						'route'      => 'taxonomytagsGetIndex',
					],
					[
						'label'      => Lang::get( 'admin/taxonomy/messages.menu-synonyms' ),
						'section'    => 'taxonomysynonyms',
						'route'      => 'taxonomyGetSynonyms',
					],
				],
			],
			[ // Catalogue de services
				'label'      => Lang::get ( 'admin/ewbsservices/messages.menu' ),
				'section'    => 'ewbsservices',
				'route'      => 'ewbsservicesGetIndex',
				'permission' => 'servicescatalog_display',
			],
			[ // Admin
				'label'      => Lang::get ( 'general.admin' ),
				'section'    => 'admin',
				'permission' => ['administrations_manage', 'ewbsmembers_manage', 'manage_roles', 'manage_users', 'jobs_manage', 'ministers_manage'],
				'submenu'    => [
					[
						'label'      => Lang::get ( 'admin/administrations/messages.menu' ),
						'section'    => 'administrations',
						'route'      => 'administrationsGetIndex',
						'permission' => 'administrations_manage',
					],[
						'label'      => Lang::get ( 'admin/ewbsmembers/messages.menu' ),
						'section'    => 'ewbsmembers',
						'route'      => 'ewbsmembersGetIndex',
						'permission' => 'manage_users',
					],[
						'label'      => Lang::get ( 'admin/jobs/messages.jobs' ),
						'section'    => 'jobs',
						'route'      => 'jobsGetIndex',
						'permission' => 'jobs_manage',
					],[
						'label'      => Lang::get ( 'admin/jobs/messages.failedjobs' ),
						'section'    => 'failedjobs',
						'route'      => 'failedjobsGetIndex',
						'permission' => 'jobs_manage',
					],[
						'label'      => Lang::get ( 'admin/ministers/messages.menu' ),
						'section'    => 'ministers',
						'route'      => 'ministersGetIndex',
						'permission' => 'ministers_manage',
					],[
						'label'      => Lang::get ( 'admin/roles/messages.menu' ),
						'section'    => 'roles',
						'route'      => 'rolesGetIndex',
						'permission' => 'manage_roles',
					],[
						'label'      => Lang::get ( 'admin/users/messages.menu' ),
						'section'    => 'users',
						'route'      => 'usersGetIndex',
						'permission' => 'manage_users',
					],
				],
			],
			[ // Corbeille
				'label'      => Lang::get ( 'general.trash' ),
				'section'    => 'trash',
				'submenu'    => [
					[
						'label'      => Lang::get ( 'admin/ewbsactions/messages.title' ),
						'section'    => 'ewbsActions-trash',
						'route'      => 'ewbsActionsGetTrash',
						'permission' => 'ewbsActions_display',
					],[
						'label'      => Lang::get ( 'admin/annexes/messages.menu' ),
						'section'    => 'annexes-trash',
						'route'      => 'annexesGetTrash',
						'permission' => 'formslibrary_display',
					],[
						'label'      => Lang::get ( 'admin/administrations/messages.menu' ),
						'section'    => 'administrations-trash',
						'route'      => 'administrationsGetTrash',
						'permission' => 'administrations_manage',
					],[
						'label'      => Lang::get ( 'admin/demarches/messages.menu' ),
						'section'    => 'demarches-trash',
						'route'      => 'demarchesGetTrash',
						'permission' => 'demarches_display',
					],[
						'label'      => Lang::get ( 'admin/eforms/messages.menu' ),
						'section'    => 'eforms-trash',
						'route'      => 'eformsGetTrash',
						'permission' => 'formslibrary_display',
					],[
						'label'      => Lang::get ( 'admin/ministers/messages.menu' ),
						'section'    => 'ministers-trash',
						'route'      => 'ministersGetTrash',
						'permission' => 'ministers_manage',
					],[
						'label'      => Lang::get ( 'admin/ideas/messages.menu' ),
						'section'    => 'ideas-trash',
						'route'      => 'ideasGetTrash',
						'permission' => 'ideas_display',
					],[
						'label'      => Lang::get ( 'admin/ewbsmembers/messages.menu' ),
						'section'    => 'ewbsmembers-trash',
						'route'      => 'ewbsmembersGetTrash',
						'permission' => 'manage_users',
					],[
						'label'      => Lang::get ( 'admin/pieces/messages.menu' ),
						'section'    => 'pieces-trash',
						'route'      => 'piecesGetTrash',
						'permission' => 'pieces_tasks_display',
					],[
						'label'      => Lang::get ( 'admin/tasks/messages.menu' ),
						'section'    => 'tasks-trash',
						'route'      => 'tasksGetTrash',
						'permission' => 'pieces_tasks_display',
					],
					[
						'label'      => Lang::get ( 'admin/taxonomy/messages.menu-categories-trash' ),
						'section'    => 'taxonomycategories-trash',
						'route'      => 'taxonomycategoriesGetTrash',
						'permission' => 'taxonomy_display',
					],
					[
						'label'      => Lang::get ( 'admin/taxonomy/messages.menu-tags-trash' ),
						'section'    => 'taxonomytags-trash',
						'route'      => 'taxonomytagsGetTrash',
						'permission' => 'taxonomy_display',
					],
					[
						'label'      => Lang::get ( 'admin/ewbsservices/messages.menu' ),
						'section'    => 'ewbsservices-trash',
						'route'      => 'ewbsservicesGetTrash',
						'permission' => 'servicescatalog_display',
					],
					[
						'label'      => Lang::get ( 'admin/users/messages.menu' ),
						'section'    => 'users-trash',
						'route'      => 'usersGetTrash',
						'permission' => 'manage_users',
					],
					/*[
						'label'      => Lang::get ( 'admin/piecesrates/messages.menu' ),
						'route'      => 'piecesratesGetTrash',
						'permission' => 'pieces_tasks_display',
					],[
						'label'      => Lang::get ( 'admin/piecestypes/messages.menu' ),
						'route'      => 'piecestypesGetTrash',
						'permission' => 'pieces_tasks_display',
					],*/
				],
			],
		];
		$menu=$this->filterSidebarMenu($menu);
		View::share('sidebarMenu', $menu);
	}
	
	/**
	 * Filter le menu selon l'utilisateur loggé
	 *
	 * @param array $menu
	 */
	private final function filterSidebarMenu($menu, $sub=false) {
		$filteredMenu=array();
		foreach($menu as $item) {
			
			//Check des rôles (nb : plus utilisé pr l'instant, mais pourrait peut-être être utile ?)
			$hasRole=false;
			if(array_key_exists('role', $item)) {
				if($this->loggedUser->hasRole($item['role']))
					$hasRole=true;
			}
			else $hasRole=true;
			
			//Check des permissions
			$hasPermission=false;
			if(array_key_exists('permission', $item)) {
				$permissions= (is_array($item['permission']) ? $item['permission'] : array($item['permission']));
				foreach($permissions as $permission) {
					if($this->loggedUser->can($permission)) {
						$hasPermission=true;
						break;
					}
				}
			}
			else $hasPermission=true;
			
			// Si interdiction par rôle ou permission, ignorer ce point de menu
			if(!$hasRole || !$hasPermission) continue;
			
			// Associer une icône à la section pour le 1e niveau
			if(!$sub)
				$item['icon']=$this->getSectionIcon($item['section']);
			
			// Propriété "active" si page courante
			if($this->section == $item['section'])
				$item['active']=true;
					
			// Sous-menu éventuel
			if(array_key_exists('submenu', $item)) {
				$item['submenu']=$this->filterSidebarMenu($item['submenu'],true);
			}
			$filteredMenu[]=$item;
		}
		return $filteredMenu;
	}




	/**
	 * Gestion du returnTo
	 * Ces fonctions ne peuvent pas être redéfinies dans les controlleurs qui héritent.
	 * Elle servent à définir la page sur laquelle retourner après une certaine action.
	 * Par exemple : retouner sur une liste bien définie lorsqu'on a terminée l'édtition d'un objet.
	 */


	/**
	 * Cette fonction sauvegarde un nom de route en session, pour effectuer un returnTo plus tard
	 * Si on ne lui passe pas de paramètre : elle sauve la route en cours
	 * Si on lui passe un paramètre : elle sauve le nom de la route (si le nom est un nom de route valide)
	 *
	 * @return bool : True si le nom a bien été sauvé. False sinon.
	 */
	final public function setReturnTo() {

		// Si on a pas passé d'argument, on sauve la route en cours
		if (func_num_args() == 0) {
			Session::set(self::SESSION_RETURNTO_ROUTENAME, Route::currentRouteName());
			return true;
		}

		// si on a passé un argument, on sauve la route (si elle existe)
		elseif (func_num_args() == 1) {
			if (Route::has(func_get_args(0))) {
				Session::set(self::SESSION_RETURNTO_ROUTENAME, func_get_arg(0));
				return true;
			}
			return false;
		}

		// si on a passé un nombre plus grand d'argument ... mauvais appel
		return false;

	}

	/**
	 * Retourne la dernière route enregistrée
	 * @return mixed|null Nom de la route ou null
	 */
	final public function getReturnTo() {
		return Session::get(self::SESSION_RETURNTO_ROUTENAME, null);
	}


}