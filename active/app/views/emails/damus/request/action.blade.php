<?php
/*@var string $link */
/*@var string $request */
?>

@extends('emails.layout')
@section('content')
<p><strong>{{ Lang::get('admin/damus/messages.request.mail.request')}}</strong></p>
<p>{{nl2br($request)}}</p>

<p><strong>{{ Lang::get('admin/damus/messages.request.mail.link')}}</strong></p>
<p><a href="{{$link}}">{{$link}}</a></p>
@stop
