<?php
/**
 * @var Barryvdh\Queue\Models\FailedJob $job
 */
$payload=json_decode($job->payload,true);
?>

@extends('site.layouts.container-fluid')
@section('title')Réinsertion d'un job dans la queue @stop
@section('content')
<div class="block-flat">
	<div class="content">
		<p>Vous allez réinsérer le job <strong>#{{str_pad($job->id, 6, "0", STR_PAD_LEFT)}}</span> {{$payload['job']}}</strong> dans la queue.</p>
		@warning('Cela suppose que le problème lié à l\'exécution de ce job a été résolu !')
		<form class="form-horizontal" method="post" autocomplete="off" action="{{ route('failedjobsPostRetry', $job->id) }}">
			<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
			<div class="form-group">
				<div class="controls">
					<a class="btn btn-cancel" href="{{ route('failedjobsGetView', $job->id) }}">{{Lang::get('button.cancel')}}</a>
					<button type="submit" class="btn btn-primary">Confirmer la réinsertion</button>
				</div>
			</div>
		</form>
	</div>
</div>
@stop