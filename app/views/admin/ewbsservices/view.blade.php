@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->name}} @stop
@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="block-flat">
			<div class="content">
				<h4>Description : </h4>
				{{$modelInstance->description}}
				<hr />
				<h4>Tags associés :</h4>
				<?php $aTags= $modelInstance->tags; ?>
				@if (!count($aTags))
					Aucun
				@else
					@foreach($aTags as $tag)
						<a href="{{route('taxonomytagsGetView', $tag->id)}}"><span class="label label-default">{{$tag->name}} ({{$tag->category->name}})</span></a>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
@stop