<?php
/**
 * Commentaires sur les idées
 *
 * @property int            $id              (PK)
 * @property int            $user_id         Obligatoire, @see User
 * @property int            $idea_id         Obligatoire, @see Idea
 * @property string         $comment         Obligatoire
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @author jdavreux
 */
class IdeaComment extends Eloquent {
	
	use SoftDeletingTrait;
	protected $table = 'ideaComments';
	public function user() {
		return $this->belongsTo ( 'User' );
	}
	public function idea() {
		return $this->belongsTo ( 'Idea' );
	}
}
