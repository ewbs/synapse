@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->name}} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<h4>Tags contenus dans cette catégorie</h4>
				@if ( count($modelInstance->tags) )
					@foreach ($modelInstance->tags as $tag)
						<a href="{{route('taxonomytagsGetView', $tag->id)}}">
							<span class="label label-default">
								{{$tag->name()}}</a>
							</span>
						</a>
					@endforeach
				@else
					Aucun
				@endif
			</div>
		</div>
	</div>
</div>
@stop