<?php
/**
 * Classe de base à toutes les modèles étant des révisions d'un autre modèle
 * 
 * On part de l'hypothèse que les modèles étendus auront bien au minimum les propriétés ci-dessous
 * 
 * @property int            $id              (PK)
 * @property int            $user_id         @see User
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * 
 * @abstract
 * @author mgrenson
 *
 */
abstract class RevisionModel extends TrashableModel {
	
	/**
	 * Liste des propriétés du RevisionModel que le RevisableModel pourra automatiquement compléter dans la révision qu'il créera
	 * 
	 * @return array
	 */
	public abstract function attributes();
	
	/**
	 * Depuis une révision, permettre de récupérer le nom sur le modèle révisable lié
	 * (et pour les cas où il n'y en n'a pas, considérer le name() éventuellement présent directement sur la révision)
	 * 
	 * {@inheritDoc}
	 * @see ManageableModel::name()
	 */
	public function name() {
		try {
			$revisable=$this->revisable()->getQuery()->withTrashed()->first();
			if($revisable)
				return $revisable->name();
		}
		catch(Exception $e) {
			Log::warning($e);
		}
		return parent::name();
	}
	
	/**
	 * Relation vers le modèle révisable
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public abstract function revisable();
	
	/**
	 * Relation vers le user
	 * 
	 * @see User
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	
	/**
	 * Route pour demander la confirmation de destruction d'une révision d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routeGetDestroy($extra) {
		return $this->route('GetDestroy', $extra);
	}
	
	/**
	 * Route pour détruire une révision d'une instance du modèle courant
	 *
	 * @param array|null $extra Un tableau de paramètres supplémentaires à placer dans l'url
	 * @return string
	 */
	public final function routePostDestroy($extra) {
		return $this->route('PostDestroy', $extra);
	}
}
