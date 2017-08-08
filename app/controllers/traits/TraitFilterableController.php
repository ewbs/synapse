<?php
namespace Synapse\Controllers\Traits;
/**
 * Class TraitFilterableController
 * Trait pour implémenter le filtrage des éléments selons les filtres définis par l'utilisateur
 */
	trait TraitFilterableController {
		/**
		 * Génère la liste des éléments filtrée par les filtres utilisateur & formatée pour les DataTables
		 * 
		 * @return Datatables JSON
		 */
		public final function getDataFiltered() {
			return $this->getDataFilteredJson();
		}
		
		/**
		 * Génère la liste des instances du modèle courant filtrés via les filtres utilisateur & formatées pour les DataTables
		 * 
		 * @return Datatables JSON
		 */
		protected abstract function getDataFilteredJson();
	}