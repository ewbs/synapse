<?php
/**
 * @var Annexe $modelInstance
 */
?>
@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->title}} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header">
				<h3>Description</h3>
			</div>
			<div class="content">
				
				@if($modelInstance->description)
				<p>{{nl2br($modelInstance->description)}}</p>
				@endif
				
				@if($modelInstance->piece_id)
				<h4>Relative à la pièce</h4>
				<p><a {{$loggedUser->can('pieces_tasks_manage') ? 'href="'.route('piecesGetEdit', $modelInstance->piece_id).'"' : ''}}>{{$modelInstance->piece->name}}</a></p>
				@endif
			</div>
		</div>
	</div>
</div>
@stop