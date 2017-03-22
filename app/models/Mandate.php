<?php
use Illuminate\Database\Eloquent\Builder;
use LaravelBook\Ardent\Ardent;
use Illuminate\Database\QueryException;

/**
 * Mandats des ministres (liaisons entre ministres et gouvernements
 * 
 * @property int    $minister_id         (PK)
 * @property int    $governement_id      (PK)
 * @property array  $mandate_range       (PK)
 * @property string $function
 * 
 * @author mgrenson
 */
class Mandate extends Ardent {
	
	protected $table = 'governement_minister';
	public $timestamps = false;
	
	public static $rules=[
		'minister_id' => 'required',
		'governement_id' => 'required',
		'mandate_range' => 'required',
	];
	
	public static $customMessages=[
		'mandate_range.required' => 'Les champs de date de début et de fin de mandat sont obligatoires',
	];
	
	
	/**
	 * Obtenir la date de début du mandat à partir du range
	 * 
	 * @return string
	 */
	public function getStart() {
		return DB::selectOne('SELECT LOWER(?::daterange) AS start',[$this->mandate_range])->start;
	}
	
	/**
	 * Obtenir la date de fin du mandat du range
	 * 
	 * @return string
	 */
	public function getEnd() {
		return DB::selectOne('SELECT UPPER(?::daterange)-1 AS end',[$this->mandate_range])->end;
	}
	
	/**
	 * Mettre à jour le range de dates
	 * 
	 * @param string $start
	 * @param string $end
	 * @return Mandate
	 */
	public function setMandateRange($start, $end) {
		if($start && $end) {
			if($start > $end)
				$this->validationErrors->add('mandate_range', 'La date de début de mandat est plus grande que la date de fin');
			else {
				try {
					$this->mandate_range=DB::selectOne('SELECT DATERANGE(?, ?, \'[]\') AS range',[$start,$end])->range;
				}
				catch(QueryException $e) {$this->validationErrors->add('mandate_range', $e->previous->getMessage());}
			}
		}
		else {
			$this->mandate_range=null;
		}
		return $this;
	}
	
	/**
	 * Query scope joignant les gouvernements
	 *
	 * @param Builder $query
	 * @return Builder
	 */
	public function scopeAll(Builder $query) {
		return $query
		->join('governements', 'governements.id', '=', 'governement_minister.governement_id')
		->addSelect(['governement_minister.id', DB::raw('LOWER(governement_minister.mandate_range) AS start'), DB::raw('UPPER(governement_minister.mandate_range)-1 AS end'), 'governements.shortname', 'governement_minister.function'])
		//->orderBy(DB::raw('LOWER(governement_minister.mandate_range)', 'DESC'))
		;
	}
	
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function minister() {
		return $this->belongsTo('Minister');
	}
	
	/**
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function governement() {
		return $this->belongsTo('Minister');
	}
}