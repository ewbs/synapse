<?php
/*
 * @var Annexes $modelInstance
 */
?>
<div class="col-md-4">
	<div class="block-flat">
		<div class="content no-margin no-padding">
			@include('admin.modelInstance.partial-features')
		</div>
	</div>
	<div class="block-flat">
		<div class="header">
			<h3><span class="fa fa-briefcase"></span> Démarches liées</h3>
		</div>
		<div class="content">
			<p>
			@if ( count($modelInstance->demarcheEforms) )
				<ul>
					@foreach ($modelInstance->demarcheEforms as $demarche_eform)
					<?php $demarche=$demarche_eform->demarche;?>
					<li>
						<a href="{{route('demarchesGetView', $demarche->id)}}">{{$demarche->name()}}</a> 
					</li> 
					@endforeach
				</ul> 
			@else 
			Aucune
			@endif
			</p>
		</div>
	</div>
</div>
