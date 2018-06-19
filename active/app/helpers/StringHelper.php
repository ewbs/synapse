<?php 
class StringHelper {
	
	public static function getStringOrNull($string) {
		$string=trim($string);
		if(strlen($string)===0) $string=null;
		return $string;
	}
}
?>