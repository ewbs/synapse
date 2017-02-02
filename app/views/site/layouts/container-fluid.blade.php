@extends('site.layouts.wrapper')
@section('container')
<div class="container-fluid" id="pcont">
	@include('notifications')
	<div class="page-head">
		<div class="row">
			<div class="{{isset($features)?'col-md-8':'col-md-12'}}">
				<h2>
					<span class="fa fa-{{$sectionIcon}}"></span>
					@yield('title')
				</h2>
			</div>
			@if(isset($features))
			<div class="col-md-4">
				<div class="pull-right">
					@include('admin.modelInstance.partial-features')
				</div>
			</div>
			@endif
		</div>
	</div>
	<div class="cl-mcont">
		@yield('content')
		@include('site.layouts.partial.footer')
	</div>
</div>
@stop