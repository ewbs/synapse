<?php
/**
 * @var Minister $modelInstance
 * @var Mandate $mandate
 */
$edit=($mandate && $mandate->id);
$governement=Input::old('governement', $edit ? $mandate->governement_id : null);
?>
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form  method="post" autocomplete="off" action="{{ $edit ? route('ministersMandatesPostEdit', [$modelInstance->id, $mandate->id]) :route('ministersMandatesPostCreate', $modelInstance->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<div class="modal-header">
					<button class="close" aria-label="Fermer" type="button" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="servermodal-title">
						{{ ($edit ? 'Edition' : 'Création') }} d'un mandat
						<span></span>
					</h3>
				</div>
				<div class="modal-body">
					
					<div class="row">
						<div class="col-sm-6">
							<!-- Début -->
							<div class="form-group {{{ $errors->has('mandate_range') ? 'has-error' : '' }}}">
								<label class="control-label" for="start">Début</label>
								<div class="input-group date datepicker">
									<input type="text" class="form-control" name="start" value="{{{ Input::old('start', $edit ? $mandate->getStart() : null) }}}"/>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
							<!-- ./ Début -->
						</div>
						<div class="col-sm-6">
							<!-- Fin -->
							<div class="form-group {{{ $errors->has('mandate_range') ? 'has-error' : '' }}}">
								<label class="control-label" for="start">Fin</label>
								<div class="input-group date datepicker">
									<input type="text" class="form-control" name="end" value="{{{ Input::old('end', $edit ? $mandate->getEnd() : null) }}}"/>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
							<!-- ./ Fin -->
						</div>
						{{ $errors->first('mandate_range', '<div class="col-sm-12 text-center"><span class="help-inline">:message</span></div>') }}
					</div>
					<div class="row">
						<div class="col-sm-6">
							<!-- Gouvernement -->
							<div class="form-group {{{ $errors->has('governement_id') ? 'has-error' : '' }}}">
								<label class="control-label" for="state">Gouvernement</label>
								<select class="form-control" name="governement">
									@foreach(Governement::all() as $g)
									<option value="{{$g->id}}"{{ $g->id==$governement ? ' selected': '' }}>{{ $g->shortname }}</option>
									@endforeach
								</select>
								{{ $errors->first('governement_id', '<span class="help-inline">:message</span>') }}
							</div>
						</div>
						<!-- ./ Gouvernement -->
						<div class="col-sm-6">
							<!-- Fonction -->
							<div class="form-group {{{ $errors->has('function') ? 'has-error' : '' }}}">
								<label class="control-label" for="function">Fonction</label>
								<textarea class="form-control" name="function" rows="3">{{{ Input::old('function', $edit ? $mandate->function : null) }}}</textarea>
								{{ $errors->first('function', '<span class="help-inline">:message</span>') }}
							</div>
							<!-- ./ Fonction -->
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" type="button" data-dismiss="modal">{{Lang::get('button.cancel')}}</button>
					<button type="submit" name="action" value="save" class="btn btn-primary">{{Lang::get('button.save')}}</button>
				</div>
			</form>
		</div>
	</div>
</div>