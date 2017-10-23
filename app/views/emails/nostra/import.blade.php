<?php
/*@var array $exceptions */
?>

@extends('emails.layout')
@section('content')
@foreach($exceptions as $exception)
<p><strong>{{ $exception['msg'] }}</strong></p>
@endforeach
<p>{{nl2br({{ $exception['e'] }})}}</p>
@stop
