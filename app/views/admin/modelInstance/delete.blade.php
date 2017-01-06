@extends('site.layouts.default') {{-- Web site Title --}}
@section('title') Bienvenue sur Synapse @parent @stop {{-- Content --}}
@section('content')

<div class="page-head">
	<h2>{{ Lang::get ( 'admin/'.$model->getModelLabel().'/messages.title' ) }}</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">
					<h3>{{ Lang::get ( 'admin/'.$model->getModelLabel().'/messages.delete.subtitle' ) }}</h3>
				</div>
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
					{{-- Delete Form --}}
					<form id="deleteForm" class="form-horizontal" method="post" autocomplete="off" action="{{ $modelInstance->routePostDelete() }}">
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						
						<p>{{ Lang::get ('admin/'.$model->getModelLabel().'/messages.delete.detail', ['name'=>$modelInstance->name()]) }}</p>
						<!-- Form Actions -->
						<div class="form-group">
							<div class="controls">
								<a class="btn btn-cancel" href="{{ $modelInstance->hasView()?$modelInstance->routeGetView():$modelInstance->routeGetIndex() }}">Annuler</a>
								<button type="submit" class="btn btn-danger">Confirmer la suppression</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
					@else
					<a class="btn btn-cancel" href="{{ $model->routeGetIndex() }}">Retour à la liste</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@stop
