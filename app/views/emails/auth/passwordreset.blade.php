<?php
/**
 * @var string $token
 */
$url=route('userGetReset', $token);
?>
@extends('emails.layout')
@section('content')
<p>{{ Lang::get('confide::confide.email.password_reset.greetings',['name' => $user['username']]) }},</p>
<p>{{ Lang::get('confide::confide.email.password_reset.body') }}</p>
<p><a href="{{$url}}">{{$url}}</a></p>
<p>{{ Lang::get('confide::confide.email.password_reset.farewell') }}</p>
@stop