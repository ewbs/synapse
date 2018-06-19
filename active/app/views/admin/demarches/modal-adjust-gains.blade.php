<?php
use Andrew13\Helpers\String;

/**
 * Modale invitant à ajuster les gains au niveau de la démarche suite à des changements effectués
 * au niveau des différents composants impactant potentiellement ces gains
 * 
 * @var Demarche $demarche
 * @var array $componentGains
 * @var string $componentType
 * @var int $componentId
 */
$gains=[
	'gain_potential_administration'=>'Gain potentiel administration',
	'gain_potential_citizen'=>'Gain potentiel usager',
	'gain_real_administration'=>'Gain effectif administration',
	'gain_real_citizen'=>'Gain effectif usager',
]
?>
<div class="modal fade noAuto colored-header warning" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<form autocomplete="off" action="{{ route('demarchesPostGains', $demarche->id) }}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				<input type="hidden" name="componentType" value="{{$componentType}}"/>
				<input type="hidden" name="componentId" value="{{$componentId}}"/>
				<div class="modal-header">
					<button class="close" type="submit" name="action" value="cancel" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</button>
					<h3 class="modal-title" id="modalCompleteTitle2">Ajustement des gains au niveau de la démarche<span></span></h3>
				</div>
				<div class="modal-body" id="modalCompleteBody2">
					<p>Le changement effectué impacte les gains suivants ayant été ajustés manuellement au niveau de la démarche :</p>
					<div class="row">
						@foreach($gains as $name=>$label)
						@if(array_key_exists($name, $componentGains))
						<div class="col-md-6 gain {{$name}}">
							<div class="form-group">
								<label for="name">{{{ $label }}}</label>
								<div class="input-group">
									<input type="hidden" name="{{$name}}" value="{{$componentGains[$name]['amount']}}"/>
									<span class="old">{{$componentGains[$name]['old']}}</span>&#160;<i class="fa fa-arrow-right"></i>&#160;<span class="new">{{$componentGains[$name]['new']}}</span>
								</div>
								{{ $errors->first($name, '<span class="help-inline">:message</span>')}}
							</div>
						</div>
						@endif
						@endforeach
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-warning" name="action" value="save">Réajuster les gains</button>
					<button type="submit" class="btn btn-default" name="action" value="cancel">Laisser les gains tels quels</button>
				</div>
			</form>
		</div>
	</div>
</div>