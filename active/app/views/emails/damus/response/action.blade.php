<?php
/*@var string $link */
/*@var string $request */
/*@var string $response */
?>

@extends('emails.layout')
@section('content')
<p><strong>{{ Lang::get('admin/damus/messages.response.mail.link')}}</strong></p>
<p><a href="{{$link}}">{{$link}}</a></p>

<p><strong>{{ Lang::get('admin/damus/messages.response.mail.request')}}</strong></p>
<p>{{nl2br($request)}}</p>

<p><strong>{{ Lang::get('admin/damus/messages.response.mail.response')}}</strong></p>
<p>{{nl2br($response)}}</p>
@stop
