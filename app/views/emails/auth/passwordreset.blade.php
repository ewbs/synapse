<?php
/**
 * @var string $token
 */
$url=route('userGetReset', $token);
?>
<h1>Synapse :: Réinitialisation du mot de passe</h1>
<p>{{ Lang::get('confide::confide.email.password_reset.greetings',['name' => $user['username']]) }},</p>
<p>{{ Lang::get('confide::confide.email.password_reset.body') }}</p>
<p><a href="{{$url}}">{{$url}}</a></p>
<p>{{ Lang::get('confide::confide.email.password_reset.farewell') }}</p>
