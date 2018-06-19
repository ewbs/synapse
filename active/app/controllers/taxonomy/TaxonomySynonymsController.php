<?php
class TaxonomySynonymsController extends TaxonomyTagsController {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ModelController::getSection()
	 */
	protected function getSection(){
		return 'taxonomysynonyms';
	}
	
	/**
	 * Rend l'écran de gestion des synonymes
	 *
	 * @return \Illuminate\View\View
	 */
	public function getSynonyms() {

		$unclassified = TaxonomyTag::whereNull('group')->orderBy('name')->get();

		$classifiedUnsorted = TaxonomyTag::whereNotNull('group')->orderBy('group')->orderBy('name')->get();
		$classified = [];
		foreach($classifiedUnsorted as $tag) {
			if (!isset($classified[$tag->group])) { $classified[$tag->group] = []; }
			array_push($classified[$tag->group], $tag);
		}
		unset($classifiedUnsorted);

		return View::make ('admin/taxonomy/synonyms', compact('unclassified', 'classified'));

	}


	/**
	 * Cette fonction est appelée en ajax pour modifier la synonymie.
	 * Retourne du json "error" avec false ou true
	 */
	public function postSynonyms() {

		if ( ! Input::has('ids') ) { //avant toute chose, si on a pas d'ids passé, on retourne une erreur
			return Response::json(['error'=>true]);
		}

		if ( Input::has('groupId') ) { //on doit passer un groupIp
			//si le groupId existe :
			if (Input::get('groupId') > 0) {
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => Input::get('groupId')]);
				return Response::json(['error' => false]);
			}

			//si on passe le group des tags non triés :
			elseif (Input::get('groupId') == "NONE") {
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => NULL]);
				return Response::json(['error' => false]);
			}

			//si le groupId n'existe pas (valeur -1) :
			else {
				$newGroupId = TaxonomyTag::max('group') + 1;
				TaxonomyTag::whereIn('id', Input::get('ids'))->update(['group' => $newGroupId]);
				return Response::json(['error'=>false, 'groupId'=>$newGroupId]);
			}

		}

		return Response::json(['error'=>true]);
	}

}
