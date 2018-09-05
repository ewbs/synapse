<?php
/**
 * @var Demarche $modelInstance
 */
$nostraDemarche=$modelInstance->nostraDemarche;
$manage = $modelInstance->canManage();
?>

@extends('site.layouts.container-fluid')
@section('title')Actions de la démarche <em>{{ $nostraDemarche ? $nostraDemarche->title : $modelInstance->title }}</em> @stop
@section('content')
<form>
	<input type="hidden" id="_token" name="_token" value="{{{ csrf_token() }}}" />
</form>
<div class="row">
	<div class="col-md-12">
		@include('admin.demarches.blocs.actions')
	</div>
</div>
@stop

@section('scripts')
<script lang="javascript">
	$(document).ready( function () {
		$("#sidebar-collapse").trigger("click"); //fermer la sidebar
	});
</script>
@stop