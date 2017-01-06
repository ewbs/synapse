@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Taxonomie sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')

	<div class="page-head">
		<h2><span class="fa fa-tag"></span> Taxonomie</h2>
	</div>
	<div class="cl-mcont">

		<div class="row">

			<div class="col-md-12">

				<div class="block-flat">
					<div class="header">
						@include('admin.modelInstance.partial-features')
						<h3>
							<span class="text-primary">{{$modelInstance->id}}</span></span>
							{{$modelInstance->name}}
						</h3>
					</div>
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