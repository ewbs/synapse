@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop
<?php
/* @var Barryvdh\Queue\Models\FailedJob $job */
$payload=json_decode($job->payload,true);
?>
{{-- Content --}}
@section('content')
<div class="page-head">
	<h2>Jobs en échec</h2>
</div>
<div class="cl-mcont">
	
	<div class="block-flat">
		<div class="header">
			<h3><span class="text-primary">Réinsertion d'un job dans la queue</h3>
		</div>
		<div class="content">
			<p>Vous allez réinsérer le job <strong>#{{str_pad($job->id, 6, "0", STR_PAD_LEFT)}}</span> {{$payload['job']}}</strong> dans la queue.</p>
			@warning('Cela suppose que le problème lié à l\'exécution de ce job a été résolu !')
			<form class="form-horizontal" method="post" autocomplete="off" action="{{ route('failedjobsPostRetry', $job->id) }}">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				<div class="form-group">
					<div class="controls">
						<a class="btn btn-cancel" href="{{ route('failedjobsGetView', $job->id) }}">Annuler</a>
						<button type="submit" class="btn btn-primary">Confirmer la réinsertion</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop