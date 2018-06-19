<?php

class NumberHelper {
	const DISPLAYED_DECIMAL_SEP=',';
	const DISPLAYED_THOUSAND_SEP='&#160;';
	const INTERNAL_DECIMAL_SEP='.';
	
	const DECIMAL_REGEX='/^(\d{1,3}( \d{3})*|\d+)(,\d+)?$/';
	
	/**
	 * Met en forme une taille de fichier, en unités kiB MiB GiB.
	 * 
	 * @param int $size la taille, en octets
	 * @return string
	 */
	public static function formatFileSize($size) {
		     if($size>0x1900000000) return round($size/0x40000000,0).' GiB';
		else if($size>0x280000000)  return round($size/0x40000000,1).' GiB';
		else if($size>0x40000000)   return round($size/0x40000000,2).' GiB';
		else if($size>0x6400000)    return round($size/0x100000  ,0).' MiB';
		else if($size>0xa00000)     return round($size/0x100000  ,1).' MiB';
		else if($size>0x100000)     return round($size/0x100000  ,2).' MiB';
		else if($size>0x19000)      return round($size/0x400     ,0).' kiB';
		else if($size>0x2800)       return round($size/0x400     ,1).' kiB';
		else if($size>0x400)        return round($size/0x400     ,2).' kiB';
		else                        return       $size              .' B';
	}
	
	/**
	 * Formate un nombre pour l'affichage
	 * 
	 * @param float $num
	 * @param number $decimals
	 * @return string
	 */
	public static function numberFormat($num, $decimals=0) {
		if(!is_numeric($num)) return '';
		return number_format($num,$decimals,self::DISPLAYED_DECIMAL_SEP, self::DISPLAYED_THOUSAND_SEP);
	}
	
	/**
	 * Formate un nombre décimal pour l'affichage en limitant automatiquement à 2 décimales
	 *
	 * @param float $num
	 * @return string
	 */
	public static function decimalFormat($num) {
		return self::numberFormat($num, 2);
	}
	
	/**
	 * Formate un nombre décimal représentant un montant financier  pour l'affichage
	 *
	 * @param float $num
	 * @return string
	 */
	public static function moneyFormat($num, $currency='€') {
		return self::decimalFormat($num).'&#160;'.$currency;
	}

	public static function moneyFormatNoDecimal($num, $currency='€') {
		return self::numberFormat($num, 0).'&#160;'.$currency;
	}
	
	/**
	 * Convertit un nombre formaté sous la forme d'une chaîne de caractères en un float (avec prise en charge des séparateurs de milliers et décimales)
	 * 
	 * Takes the last comma or dot (if any) to make a clean float, ignoring thousand separator, currency or any other letter
	 * @param string $num
	 * @param string $thousandSep
	 * @param string $decimalSep
	 * @return float
	 * @link http://php.net/manual/fr/function.floatval.php#114486
	 */
	public static function stringTofloat($num, $thousandSep=self::DISPLAYED_THOUSAND_SEP, $decimalSep=self::DISPLAYED_DECIMAL_SEP) {
		$thousandPos = strrpos($num, $thousandSep);
		$decimalPos = strrpos($num, $decimalSep);
		$sep = (($thousandPos > $decimalPos ) && $thousandPos) ? $thousandPos : 
		      ((($decimalPos  > $thousandPos) && $decimalPos ) ? $decimalPos : false);
		if (!$sep)
			return floatval(preg_replace("/[^0-9]/", "", $num));
		return floatval(
			preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . self::INTERNAL_DECIMAL_SEP .
			preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
		);
	}
	
	/**
	 * Convertit un nombre formaté sous la forme d'une chaîne de caractères en un float arrondi à 2 décimales (avec prise en charge des séparateurs de milliers et décimales)
	 * 
	 * @param string $num
	 * @param string $thousandSep
	 * @param string $decimalSep
	 * @return float
	 * @see self::stringTofloat()
	 */
	public static function stringToRoundedFloat($num, $thousandSep=NumberHelper::DISPLAYED_THOUSAND_SEP, $decimalSep=NumberHelper::DISPLAYED_DECIMAL_SEP) {
		return floatval(number_format(NumberHelper::stringTofloat($num, $thousandSep, $decimalSep), 2, self::INTERNAL_DECIMAL_SEP, ''));
	}
}
