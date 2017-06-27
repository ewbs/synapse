<?php
/**
 * Historique d'une action affichée en timeline
*
* @var EwbsAction $modelInstance
*/
?>
<ul class="timeline">
	@foreach ($modelInstance->getHistory() as $history)
	<li>
		<i class="fa fa-cogs {{$history->state}}"></i> <span class="date">{{$history->created_at->format ( 'j M' )}}<br/>{{$history->created_at->format ( 'H:i' )}}</span>
		<div class="content">
			<p>
				@if ($history->username)
				{{Gravatarer::make( ['email' => $history->usermail, 'size' => 30, 'secured' => true] )->html( ['class' => 'avatar pull-right'] )}}
				<strong>{{$history->username}}</strong>
				@else
				{{HTML::image('images/logo.png', 'Synapse', ['class' => 'pull-right', 'style' => 'width:30px; height:30px;'])}}
				<strong><em>La matrice</em></strong>
				@endif
				a modifié l'action à l'état en <strong>{{Lang::get( "admin/ewbsactions/messages.state.".$history->state)}}</strong>
				@if ($history->username) assignée à <strong>{{$history->responsible_username}}</strong>@endif
				 et de priorité {{EwbsActionRevision::graphicPriority($history->priority)}}
			</p>
			<hr/>
			<p class="comment-content">{{nl2br($history->description)}}</p>
		</div>
	</li>
	@endforeach
</ul>