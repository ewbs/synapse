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