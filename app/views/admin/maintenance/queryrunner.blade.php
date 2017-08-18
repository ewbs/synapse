@extends('site.layouts.container')
@section('title')Query runner @stop
@section('content')
<div class="row">
	<div class="col-md-12">
		<div class="block-flat">
			<div class="content">
				<div>
					<strong>
					Permet l'éxécution d'une ou plusieurs requêtes SQL, au sein d'une même transaction.<br/>
					Les caractères -- peuvent être utilisés pour commenter.
					</strong>
				</div>
				<form method="post" autocomplete="off" action="{{route('queryrunnerPostIndex')}}">
					<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
					<div class="form-group">
						<textarea class="form-control" rows="12" name="q" required>{{{ Input::old('q') }}}</textarea>
					</div>
					<div class="form-group">
						<label class="radio-inline"><input type="radio" name="transaction" value="rollback" checked/>Rollback</label>{{--Tjs remettre à rollback par défaut, histoire de limiter les risques de commiter trop vite !--}}
						<label class="radio-inline"><input type="radio" name="transaction" value="commit"/>Commit</label>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary">{{Lang::get('button.go')}}</button>
					</div>
				</form>
			</div>
		</div>
		
		@if(isset($results))
			@foreach($results as $query=>$result)
			<div class="block-flat">
				<div class="header">
					<h3>{{{$query}}}</h3>
				</div>
				<div class="content">
					@if(is_array($result))
					@if(!empty($result))
					<div>
						<table class="table table-hover">
						<thead>
							<tr>
							@foreach($result[0] as $key=>$cell)
								<th>{{$key}}</th>
							@endforeach
							</tr>
						</thead>
						<tbody>
						@foreach($result as $row)
							<tr>
							@foreach($row as $cell)
								<td>{{$cell}}</td>
							@endforeach
							</tr>
						@endforeach
						</tbody>
						</table>
					</div>
					@endif
					@elseif(is_bool($result))
					{{$result?'True':'False'}}
					@else
					{{$result}} row(s) affected
					@endif
				</div>
			</div>
			@endforeach
		@endif
	</div>
</div>
@stop