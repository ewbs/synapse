<?php
/**
 * Evolutions des états sur les projets des simplif'
 *
 * @property int            $id              (PK)
 * @property int            $idea_id         Obligatoire, @see Idea
 * @property int            $idea_state_id   Obligatoire, @see IdeaState
 * @property int            $user_id         Obligatoire, @see User
 * @property string         $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class IdeaStateModification extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'ideaStateModifications';
	
	// Lien entre les rôles/permissions de Synapse et les status applicables selon le statut en cours ...
	// Structure : "status en cours" -> "rôle" -> "statut disponibles"
	protected static $availableStates = array (
		'ENCODEE' => array (
			'admin' => array (
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'owner' => array (),
			'ewbs' => array () 
		),
		'REVUE' => array (
			'admin' => array (
				'ENCODEE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'owner' => array (
				'ENCODEE' 
			),
			'ewbs' => array () 
		),
		'VALIDEE' => array (
			'admin' => array (
				'ENCODEE',
				'REVUE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'REVUE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'owner' => array (
				'ENREALISATION' 
			),
			'ewbs' => array (
				'ENREALISATION' 
			) 
		),
		'ENREALISATION' => array (
			'admin' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'REALISEE',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'owner' => array (
				'VALIDEE' 
			),
			'ewbs' => array (
				'VALIDEE',
				'REALISEE',
				'SUSPENDUE' 
			) 
		),
		'REALISEE' => array (
			'admin' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'SUSPENDUE',
				'ABANDONNEE' 
			),
			'owner' => array (),
			'ewbs' => array (
				'ENREALISATION' 
			) 
		),
		'SUSPENDUE' => array (
			'admin' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'ABANDONNEE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'ABANDONNEE' 
			),
			'owner' => array (),
			'ewbs' => array (
				'ENREALISATION' 
			) 
		),
		'ABANDONNEE' => array (
			'admin' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE' 
			),
			'ideas_manage' => array (
				'ENCODEE',
				'REVUE',
				'VALIDEE',
				'ENREALISATION',
				'REALISEE',
				'SUSPENDUE' 
			),
			'owner' => array (),
			'ewbs' => array (
				'ENREALISATION' 
			) 
		) 
	);
	public function ideaState() {
		return $this->belongsTo ( 'IdeaState' );
	}
	public function idea() {
		return $this->belongsTo ( 'Idea' );
	}
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	public static function getAvailableStates($fromstate = '', $accreditation = '') {
		if (isset ( IdeaStateModification::$availableStates [$fromstate] [$accreditation] )) {
			/* Si des états sont bien disponibles en fct de l'état de départ et de l'accréditation, repartir des états en DB et filtrer la liste obtenue :
			 * (pour pouvoir inclure dans la liste retournée l'état actuel au bon endroit)
			 */
			$availableStates=IdeaStateModification::$availableStates [$fromstate] [$accreditation];
			$states=IdeaState::orderBy('order')->get(['name']);
			$effectiveStates=[];
			foreach($states as $state) {
				if($state->name==$fromstate || in_array($state->name, $availableStates)) {
					$effectiveStates[]=$state->name;
				}
			}
			return $effectiveStates;
		} elseif (!empty($fromstate)) {
			return array($fromstate);
		}
		else {
			return [];
		}
	}
}
