<?php 
/**
 * @var ManageableModel $model
 */
?>
@extends('site.layouts.container-fluid')
@section('title'){{Lang::get('admin/'.$model->getModelLabel().'/messages.delete.subtitle')}} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				@if($links)
					@if($model->deletableIfLinked())
						@warning( Lang::get ( 'admin/'.$model->getModelLabel().'/messages.delete.linked' ) )
					@else
						@error( Lang::get ( 'admin/'.$model->getModelLabel().'/messages.delete.linked' ) )
					@endif
					@foreach ($links as $linktype)
					<div>
						<h4>{{ $linktype['label'] }} ({{ count($linktype['items']) }})</h4>
						<ul>
							@foreach ($linktype['items'] as $item)
							<li>
								@if(empty($item['deleted_at']))
								<a href="{{ route($linktype['route'], $item['id']) }}" target="_blank">{{ $item['name'] }}</a>
								@else
								{{ $item['name'] }} (Dans la corbeille depuis le <i>{{ $item['deleted_at'] }}</i>)
								@endif
							</li>
							@endforeach
						</ul>
					</div>
					@endforeach
					<p><br/></p>
				@endif
				
				@if(empty($links) || $model->deletableIfLinked())
				<form id="deleteForm" class="form-horizontal" method="post" autocomplete="off" action="{{ $modelInstance->routePostDelete() }}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<p>{{ Lang::get ('admin/'.$model->getModelLabel().'/messages.delete.detail', ['name'=>$modelInstance->name()]) }}</p>
					<div class="controls">
						<a class="btn btn-cancel" href="{{ $modelInstance->hasView()?$modelInstance->routeGetView():$modelInstance->routeGetIndex() }}">{{Lang::get('button.cancel')}}</a>
						<button type="submit" class="btn btn-danger">Confirmer la suppression</button>
					</div>
				</form>
				@else
					@if($model->hasView())
					<a class="btn btn-cancel" href="{{ $modelInstance->routeGetView() }}">Retour au détail</a>
					@else
					<a class="btn btn-cancel" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
					@endif
				@endif
			</div>
		</div>
	</div>
</div>
@stop
