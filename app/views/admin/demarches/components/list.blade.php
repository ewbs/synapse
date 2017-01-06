@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')
<?php
	$nostraDemarche=$modelInstance->nostraDemarche;
	$manage = $modelInstance->canManage();
?>
<div class="page-head">
	<h2><span class="fa fa-briefcase"></span> Pièces de la démarche <em><a href="{{$modelInstance->routeGetView()}}">{{ $nostraDemarche->title }}</a></em></h2>
</div>

<form>
	<!-- CSRF Token -->
	<input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}" />
	<!-- ./ csrf token -->
</form>

<div class="cl-mcont">
	<div class="row">
		<div class="col-md-8">
			@include('admin.demarches.blocs.components')
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
			@include('admin.demarches.blocs.projets_lies',['manage'=>true])
			@include('admin.demarches.blocs.infos_nostra',['manage'=>true])
		</div>
	</div>
</div>
{{--@include('admin.demarches.piecesandtasks.partial-manage')--}}
@stop

@section('scripts')
<script lang="javascript">
	$(document).ready( function () {
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop {{-- Scripts --}}