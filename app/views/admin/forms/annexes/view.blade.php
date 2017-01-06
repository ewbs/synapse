<?php
/*
 * @var Annexe $modelInstance
 */
?>

@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-head">
	<h2><span class="fa fa-wpforms"></span> <span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->title}}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-8">
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
		<div class="col-md-4">
			<div class="block-flat">
				<div class="content no-padding no-margin">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
		</div>
	</div>
</div>
@stop