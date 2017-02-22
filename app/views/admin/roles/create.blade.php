@extends('site.layouts.container-fluid')
@section('title')Création d'un rôle @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<div class="tab-container">

					<!-- Tabs -->
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
						<li><a href="#tab-permissions" data-toggle="tab">Permissions</a></li>
					</ul>
					<!-- ./ tabs -->

					{{-- Create Role Form --}}
					<form class="form-horizontal" method="post" action=""
						autocomplete="off">
						<!-- CSRF Token -->
						<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
						<!-- ./ csrf token -->

						<!-- Tabs Content -->
						<div class="tab-content">

							<!-- Tab General -->
							<div class="tab-pane active" id="tab-general">
								<!-- Name -->
								<div
									class="form-group {{{ $errors->has('name') ? 'error' : '' }}}">
									<label class="col-md-2 control-label" for="name">Nom</label>
									<div class="col-md-10">
										<input class="form-control" type="text" name="name" id="name"
											value="{{{ Input::old('name') }}}" /> {{
										$errors->first('name', '<span class="help-inline">:message</span>')
										}}
									</div>
								</div>
								<!-- ./ name -->
							</div>
							<!-- ./ tab general -->

							<!-- Permissions tab -->
							<div class="tab-pane" id="tab-permissions">
								<div class="form-group">
									@foreach ($permissions as $permission)
									<div class="radio">
										<label> <input class="control-label" type="hidden"
											id="permissions[{{{ $permission['id'] }}}]"
											name="permissions[{{{ $permission['id'] }}}]" value="0" /> <input
											type="checkbox" class="icheck"
											id="permissions[{{{ $permission['id'] }}}]"
											name="permissions[{{{ $permission['id'] }}}]" value="1"
											{{{ (isset($permission['checked']) && $permission['checked'] ==
											true ? ' checked="checked" ' : '')}}} /> {{{
											$permission['display_name'] }}}
										</label>
									</div>
									@endforeach
								</div>
							</div>
							<!-- ./ permissions tab -->
						</div>
						<!-- ./ tabs content -->

						<!-- Form Actions -->
						<div class="form-group">
							<div class="col-md-offset-2 col-md-10">
								<a class="btn btn-cancel" href="{{route('rolesGetIndex')}}">{{Lang::get('button.cancel')}}</a>
								<button type="submit" class="btn btn-primary">{{Lang::get('button.save')}}</button>
							</div>
						</div>
						<!-- ./ form actions -->
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
