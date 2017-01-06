@extends('site.layouts.default')
{{-- Web site Title --}}
@section('title')
Bienvenue sur Synapse
@parent
@stop

{{-- Content --}}
@section('content')
<?php
/* @var Barryvdh\Queue\Models\Job $job */
$payload=json_decode($job->payload,true);
?>
<div class="page-head">
	<h2>Jobs</h2>
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
					
					<h4>Etat</h4>
					<p>{{$job->statustext()}}</p>
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
					
					@if($job->retries)
					<h4>Essais</h4>
					<p>{{$job->retries}}</p>
					@endif
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="block-flat">
				<a class="btn btn-cancel" href="{{{ route('jobsGetIndex') }}}">Retour à la liste</a><br/>
			</div>
			<div class="block-flat">
				<p><span class="fa fa-calendar"></span> Créé le {{DateHelper::datetime($job->created_at)}}</p>
				@if($job->updated_at)
				<p><span class="fa fa-calendar"></span> MAJ le {{DateHelper::datetime($job->updated_at)}}</p>
				@endif
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