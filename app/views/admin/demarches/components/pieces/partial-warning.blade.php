<?php
/**
 * Warning concernant les liens entre pièces, annexes et formulaires
 * 
 * @var array $aNotLinkedPieces
 */
?>
@if(!empty($aNotLinkedPieces))
	<div class="alert alert-warning alert-white rounded">
		<div class="icon"><i class="fa fa-exclamation-triangle"></i></div>
		<p>
		<strong>Attention! </strong><br/>
		Les pièces suivantes sont liées à des annexes de formulaires liés ci-dessus à la démarche courante, mais ne se trouvent pas parmis les pièces ci-dessous.<br/>Vous pouvez si vous le souhaitez les ajouter :
		</p>
		<p>
		@foreach($aNotLinkedPieces as $notLinkedPiece)
			<a href="javascript:void(0);" title="Ajouter" class="edit btn btn-xs btn-default" data-id="{{$notLinkedPiece->id}}">{{$notLinkedPiece->name}} <i class="fa fa-plus"></i></a>
			<br/>
		@endforeach
		</p>
	</div>
@endif