<?php


/**
 * Crée un nouvel objet PHPExcel avec les meta données par défaut (ou en paramètre)
 * @param string $creator
 * @param string $title
 * @return PHPExcel
 */
function xlsexport_getNewHandler($creator="eWBS - Synapse", $title="Export Synapse") {
	
	$objPHPExcel = new PHPExcel ();
	$objPHPExcel->getProperties ()->setCreator ( $creator );
	$objPHPExcel->getProperties ()->setLastModifiedBy ( $creator );
	$objPHPExcel->getProperties ()->setTitle ( $title );
	$objPHPExcel->getProperties ()->setSubject ( $title );
	$objPHPExcel->getProperties ()->setDescription ( $title );
	$objPHPExcel->getDefaultStyle ()->getFont ()->setName ( 'Arial' );
	$objPHPExcel->getDefaultStyle ()->getFont ()->setSize ( 9 );
	
	return($objPHPExcel);
	
}

/**
 * Dans le contexte de l'export SCM, rend la liste des colonnes éditables
 * 
 * @return string[][]
 */
function scmexport_getDemarcheComponentColumns() {
	return [
		'volume'                        =>['pos'=>'D' ,'type'=>'int' ],
		'frequency'                     =>['pos'=>'E' ,'type'=>'int' ],
		'cost_citizen_currency'         =>['pos'=>'F' ,'type'=>'dec' ],
		'cost_administration_currency'  =>['pos'=>'G' ,'type'=>'dec' ],
		'gain_potential_citizen'        =>['pos'=>'H' ,'type'=>'dec', 'calculated'=>true ],
		'gain_potential_administration' =>['pos'=>'I' ,'type'=>'dec', 'calculated'=>true ],
		'gain_real_citizen'             =>['pos'=>'J' ,'type'=>'dec' ],
		'gain_real_administration'      =>['pos'=>'K' ,'type'=>'dec' ],
		'comment'                       =>['pos'=>'L' ,'type'=>'char'],
	];
}

/**
 * Dans le contexte de l'export SCM, retourne la dernière colonne éditable
 * 
 * @return string la lettre correspondant à la colonne dans le fichier excel
 */
function scmexport_getLastDemarcheComponentColumnPosition() {
	$a=scmexport_getDemarcheComponentColumns();
	return end($a)['pos'];
}


/**
 * Retourne un style selon son nom (si passé en argument). Retourne l'ensemble des styles si aucun argument
 * Retourne null si style inexistant
 * @param string $style
 * @return array|null
 */
function xlsexport_getStyles($style="") {
	
	//ecriture blanche sur fond noir
	$styles ['white_on_blue'] = array (
		'fill' => array (
			'type' => PHPExcel_Style_Fill::FILL_SOLID,
			'color' => array (
				'rgb' => '4E9DFF' 
			) 
		),
		'font' => array (
			'color' => array (
				'rgb' => 'FFFFFF' 
			) 
		) 
	);
	
	//ecriture bleue sur fond blanc
	$styles ['blue_on_white'] = array (
		'font' => array (
			'color' => array (
				'rgb' => '4E9DFF' 
			) 
		) 
	);
	
	//titre d'une feuille excel
	$styles ['big_title'] = array(
		'font' => array(
			'size' => 16,
			'bold' => false
		)
	);
			
			
	if (strlen($style)) {
		return (isset($styles[$style]) ? $styles[$style] : null);
	}
	
	return ($styles);
	
}
