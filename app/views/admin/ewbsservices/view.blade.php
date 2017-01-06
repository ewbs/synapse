@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
	Catalogue de services sur Synapse
	@parent
@stop

{{-- Content --}}
@section('content')

	<div class="page-head">
		<h2><span class="fa fa-wrench"></span> Catalogue de services</h2>
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