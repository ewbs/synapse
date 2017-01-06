<?php
/*
 * @var EwbsAction $action
 * @var string $token
 * @var string $type
 * @var EwbsActionRevision $actionRevision
 */
$revision=$action->getLastRevision();
$step=$revision->state==EwbsActionRevision::$STATE_TODO ? 'process' : 'close';
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
	<h2>{{Lang::get("admin/damus/messages.request.{$type}.title")}}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-6">
			
			<div class="block-flat">
				<div class="header"><h3>{{Lang::get('admin/damus/messages.response.subtitle.request')}}</h3></div>
				<div class="content">
					<ul class="timeline">
						@foreach ($action->getHistory() as $history)
							<li><?php //TODO : J'ai inversé l'ordre de la liste, et présenté graphiquement l'état. Mais du coup l'état abandonné ne colle pas au cogs de Julian => voir avec lui. Adapter aussi en fct ds le détail d'une action (ds l'admin) ?>
								<i class="fa fa-cogs {{$history->state}}"></i> <span class="date">{{$history->created_at->format ( 'j M' )}}<br/>{{$history->created_at->format ( 'H:i' )}}</span>
								<div class="content">
									<p>
										{{EwbsActionRevision::graphicState($history->state)}} par
										@if ($history->username)
											{{Gravatarer::make( ['email' => $history->usermail, 'size' => 30, 'secured' => true] )->html( ['class' => 'avatar pull-right'] )}}
											<strong> {{$history->username}}</strong>
										@else
											{{HTML::image('images/logo.png', 'Synapse', ['class' => 'pull-right', 'style' => 'width:30px; height:30px;'])}}
											<strong> l'équipe NOSTRA</strong>
										@endif
									</p>
									<hr/>
									<p class="comment-content">{{nl2br($history->description)}}</p>
								</div>
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
		
		@if($revision->state==EwbsActionRevision::$STATE_TODO || $revision->state==EwbsActionRevision::$STATE_PROGRESS)
		<div class="col-md-6">
			<div class="block-flat">
				<div class="header"><h3>{{Lang::get("admin/damus/messages.response.subtitle.{$step}")}}</h3></div>
				<div class="content">
					<form class="form-horizontal" method="post" autocomplete="off" action="{{route('damusPostResponse', [$action->id, $token])}}">
						<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
						
						@if($step=='close')
						<label>{{Lang::get('admin/damus/messages.response.reasons')}}</label>
						<div class="col-xs-offset-1 spacer-bottom-xs">
							@foreach(['complete', 'partial', 'refused'] as $reason)
							<div class="radio">
								<label>
									<input type="radio" name="reason" value="{{$reason}}" required/>{{Lang::get("admin/damus/messages.response.{$type}.reasons.{$reason}.title")}}
									<br/><i class="text-muted">{{Lang::get("admin/damus/messages.response.{$type}.reasons.{$reason}.info")}}</i>
								</label>
							</div>
							@endforeach
						</div>
						@endif
						
						<label>{{Lang::get("admin/damus/messages.response.{$step}.detail")}}</label>
						<div class="col-xs-offset-1 spacer-bottom-xs">
							<textarea class="form-control" name="detail" rows="8" required></textarea>
						</div>
						<div class="col-xs-offset-1 spacer-bottom-xs">
							<button type="submit" class="btn btn-primary">{{Lang::get("admin/damus/messages.response.{$step}.action")}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		@endif
	</div>
</div>
@stop