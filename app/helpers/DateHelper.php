<?php

/**
 * Classe utilitaire facilitant l'affichage des dates au départ de champs timestamp
 * 
 * @author mgrenson
 *
 */
class DateHelper {
	
	/**
	 * Retourne la date et heure
	 * 
	 * @param int $time le timestamp à formater
	 * @param boolean $seconds Inclure les secondes, false par défaut
	 * @return string
	 */
	public static function datetime($time, $seconds=false) {
		return self::_date($time, 'd-m-Y H:i'.($seconds?':s':'') );
	}
	
	/**
	 * Retourne la date et heure au format triable (année - mois - jour)
	 *
	 * @param int $time le timestamp à formater
	 * @param boolean $seconds Inclure les secondes, false par défaut
	 * @return string
	 */
	public static function sortabledatetime($time, $seconds=false) {
		return self::_date($time, 'Y-m-d H:i'.($seconds?':s':'') );
	}
	
	/**
	 * Retourne la date
	 *
	 * @param int $time le timestamp à formater
	 * @return string
	 */
	public static function date($time) {
		return self::_date($time, 'd-m-Y' );
	}
	
	/**
	 * Retourne la date et heure au format triable (année - mois - jour)
	 *
	 * @param int $time le timestamp à formater
	 * @param boolean $seconds Inclure les secondes, false par défaut
	 * @return string
	 */
	public static function sortabledate($time) {
		return self::_date($time, 'Y-m-d' );
	}
	
	/**
	 * Retourne l'année
	 *
	 * @param int $time le timestamp à formater
	 * @return string
	 */
	public static function year($time) {
		return self::_date($time, 'Y' );
	}
	
	private static function _date($time, $format) {
		return Carbon::parse ( $time )->format ( $format );
	}
}
