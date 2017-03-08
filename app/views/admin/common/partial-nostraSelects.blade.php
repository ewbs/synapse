<?php
$selectDemarche=!empty($selectDemarche);
// ces tableaux contiennent soit les valeurs passées en post, soit les valeurs de l'objet chargé
$aSelectedNostraPublics = Input::old('nostra_publics', isset($aSelectedNostraPublics) ? $aSelectedNostraPublics : array() );
$aSelectedNostraThematiquesabc = Input::old('nostra_thematiquesabc', isset($aSelectedNostraThematiquesabc) ? $aSelectedNostraThematiquesabc : array() );
$aSelectedNostraThematiquesadm = Input::old('nostra_thematiquesadm', isset($aSelectedNostraThematiquesadm) ? $aSelectedNostraThematiquesadm : array() );
$aSelectedNostraEvenements = Input::old('nostra_evenements', isset($aSelectedNostraEvenements) ? $aSelectedNostraEvenements : array() );
$aSelectedNostraDemarches = Input::old('nostra_demarches', isset($aSelectedNostraDemarches) ? $aSelectedNostraDemarches : array() );
?>
@section('scripts')
<script lang="javascript">
	var preSelectedNostraPublics = [<?php print(implode(',', $aSelectedNostraPublics)); ?>];
	var preSelectedNostraThematiquesabc = [<?php print(implode(',', $aSelectedNostraThematiquesabc)); ?>];
	var preSelectedNostraThematiquesadm = [<?php print(implode(',', $aSelectedNostraThematiquesadm)); ?>];
	var preSelectedNostraEvenements = [<?php print(implode(',', $aSelectedNostraEvenements)); ?>];
	var preSelectedNostraDemarches = [<?php print(implode(',', $aSelectedNostraDemarches)); ?>];
</script>
{{ HTML::script('js/synapse/common/nostraSelects.js') }}
@stop
<div id="nostra-selects">
	<div class="form-group {{{ $errors->has('nostra_publics') ? 'has-error' : '' }}}">
		<label class="col-md-2 control-label" for="nostra_publics">Public(s) cible <span class="badge" id="countNostraPublics">0</span></label>
		<div class="col-md-10">
			<select class="select2 nostra" multiple name="nostra_publics[]" id="nostra_publics" required></select> {{ $errors->first('nostra_publics', '<span class="help-inline">:message</span>') }}
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label" for="nostra_thematiquesabc">Thématiques(s) usager <span class="badge" id="countNostraThematiquesabc">0</span></label>
		<div class="col-md-10">
			<select class="select2 nostra" multiple name="nostra_thematiquesabc[]" id="nostra_thematiquesabc"></select> @optional
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label" for="nostra_evenements">Evénements déclencheurs <span class="badge" id="countNostraEvenements">0</span></label>
		<div class="col-md-10">
			<select class="select2 nostra" multiple name="nostra_evenements[]" id="nostra_evenements"></select> @optional
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-2 control-label" for="nostra_thematiquesadm">Thématiques(s) administrative(s) <span class="badge" id="countNostraThematiquesadm">0</span></label>
		<div class="col-md-10">
			<select class="select2 nostra" multiple name="nostra_thematiquesadm[]" id="nostra_thematiquesadm"></select> @optional
		</div>
	</div>
	
	@if($selectDemarche)
	<div class="form-group">
		<label class="col-md-2 control-label" for="nostra_demarches">Démarches <span class="badge" id="countNostraDemarches">0</span></label>
		<div class="col-md-10">
			<select class="select2 nostra" multiple name="nostra_demarches[]" id="nostra_demarches"></select> @optional
		</div>
	</div>
	@endif
</div>