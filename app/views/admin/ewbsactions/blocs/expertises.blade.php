<?php 
/*
 * @var array $aPoles
 * @var int $totalActions
 * @var boolean $largedisplay
 */
if(!isset($largedisplay)) $largedisplay=false;

// Considérer le nb de pôles pour déterminer la répartition par colonne (sauf si on est déjà dans un affichage petit, auquel cas d'office l'un sous l'autre
$countpoles=count($aPoles);
if(!$largedisplay) $countpoles=1;
$colmdpole=($countpoles>=3?'col-md-4':($countpoles==2?'col-md-6':'col-md-12'));
?>
<div class="block-flat">
	<div class="header">
		<h3>Actions</h3>
	</div>
	<div class="content no-padding">
		@if(isset($totalActions))
		<div class="row">
			<div class="col-md-12">
				<i class="fa fa-magic fa-4x pull-left color-warning"></i>
				<h3 class="no-margin">{{$totalActions}} ACTIONS</h3>
				<p><span class="color-warning">A faire, en cours ou en standby sur des projets de simplif', démarches ou formulaires</span></p>
			</div>
		</div>
		@endif
		<div class="row">
			@foreach($aPoles as $pole)
			<div class="{{$colmdpole}} spacer-bottom-sm">
				<ul class="banded bordered">
					@foreach($pole['expertises'] as $expertise)
					<li><span class="badge badge-default pull-right">{{$expertise->actions}}</span>{{{$expertise->name()}}}</li>
					@endforeach
				</ul>
			</div>
			@endforeach
		</div>
	</div>
</div>