{{-- Modale permettant de lier un projet à la démarche --}}
<div class="modal fade noAuto colored-header" id="servermodal" role="dialog" aria-labelledby="servermodal-title" data-url="{{ route('demarchesIdeasGetLink', $demarche->id) }}">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<form class="form-horizontal">
				<div class="modal-header">
					<a class="close" data-dismiss="modal" aria-label="Fermer">
						<span aria-hidden="true">&times;</span>
					</a>
					<h3 class="modal-title" id="servermodal-title">{{ Lang::get('admin/demarches/messages.idea.modalLink.title') }}<span></span></h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-5">
							<select class="form-control select2" id="projects">
								<option></option>
								@foreach($aIdeas as $idea)
								<option data-url="{{ route('ideasGetEdit', $idea->id) }}">{{DateHelper::year($idea->created_at)}}-{{$idea->id}} {{ $idea->name }}</option>
								@endforeach
							</select>
							<script>
								$('#projects').select2({
									placeholder:'Sélectionner parmi un projet existant',
									width : '100%'
								})
								.on("select2:select", function (e) {
									window.location.replace($(this).children('option:selected').attr('data-url'));
								});
							</script>
						</div>
						<div class="col-xs-2" style="font-size:40px; text-align:center"><i class="fa fa-arrows-h" aria-hidden="true"></i></div>
						<div class="col-xs-5">
							<a class="btn btn-primary form-control" href="{{ route('ideasGetCreate') }}"  @if(!$loggedUser->can('ideas_encode')) disabled @endif>Créer un nouveau projet</a>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a class="btn btn-default cancel" data-dismiss="modal">{{ Lang::get('button.cancel') }}</a>
				</div>
			</form>
		</div>
	</div>
</div>