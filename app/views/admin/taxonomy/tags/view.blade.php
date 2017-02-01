@extends('site.layouts.container-fluid')
@section('title')<span class="text-primary">{{$modelInstance->formatedId()}}</span> {{$modelInstance->name}} @stop
@section('content')
<div class="row">
	<div class="col-md-8">
		<div class="block-flat">
			<div class="content">
				<h4>Catégorie : </h4>
				{{$modelInstance->category->name}}
				<hr />
				<h4>Synonymes :</h4>
				<?php $aSynonyms = $modelInstance->synonyms(); ?>
				@if (!count($aSynonyms))
					Aucun
				@else
					@foreach($aSynonyms as $syn)
					<a href="{{route('taxonomytagsGetView', $syn->id)}}"><span class="label label-default">{{$syn->name}}</span></a>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
@stop