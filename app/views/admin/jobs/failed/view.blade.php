@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop

{{-- Content --}}
@section('content')
<?php
/* @var Barryvdh\Queue\Models\FailedJob $job */
$payload=json_decode($job->payload,true);
?>
<div class="page-head">
	<h2>Jobs en échec</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-8">
			<div class="block-flat">
				<div class="header">
					<h3><span class="text-primary">#{{str_pad($job->id, 6, "0", STR_PAD_LEFT)}}</span> {{$payload['job']}}</h3>
				</div>
				<div class="content">
					<h4>Queue</h4>
					<p>{{$job->queue}}</p>
					<hr/>
					
					<h4>Méthode</h4>
					<p>{{$payload['job']}}</p>
					<hr/>
					
					<h4>Vue</h4>
					<p>{{$payload['data']['view']}}</p>
					<ul>
					@foreach($payload['data']['data'] as $key=>$value)
						<li>{{$key}} : {{$value}}</li>
					@endforeach
					</ul>
					<hr/>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<a class="btn btn-cancel" href="{{{ route('failedjobsGetIndex') }}}">Retour à la liste</a><br/>
				<a class="btn btn-primary" href="{{{ route('failedjobsGetRetry', $job->id) }}}">Réinsérer dans la queue</a><br/>
			</div>
			<div class="block-flat">
				<p><span class="fa fa-calendar"></span> Echoué le {{DateHelper::datetime($job->failed_at)}}</p>
			</div>
		</div>
	</div>
	@if(isset($payload['error']))
		<div class="block-flat">
		<h4>Erreur</h4>
		<p class="text-danger">{{nl2br($payload['error'])}}</p>
		<hr/>
	</div>
	@endif
</div>
@stop