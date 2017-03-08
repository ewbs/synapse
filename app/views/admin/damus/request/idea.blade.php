@extends('site.layouts.container-fluid')
@section('title'){{Lang::get('admin/damus/messages.request.idea.title')}} @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="header"><h3>{{Lang::get('admin/damus/messages.request.idea.title')}}</h3></div>
			<div class="content">
				<form class="form-horizontal" method="post" autocomplete="off" action="{{route('damusPostRequestIdea', $idea->id)}}">
					<input type="hidden" name="_token" id="_token" value="{{{ csrf_token() }}}" />
					<div class="form-group">
						<input class="form-control" type="text" name="subject" placeholder="Intitulé" value="{{{ Input::old('subject', '') }}}" required/>
					</div>
					<div class="form-group">
						<textarea class="form-control" name="comment" placeholder="Description de votre demande" rows="8" required></textarea>
					</div>
					<div class="form-group">
						<a class="btn btn-cancel" href="{{ route('ideasGetEdit', $idea->id) }}#nostraRequest">{{Lang::get('button.cancel')}}</a>
						<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop