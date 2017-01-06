<h1>Synapse :: Réinitialisation du mot de passe</h1>

<p>{{ Lang::get('confide::confide.email.password_reset.greetings',
	array( 'name' => $user['username'])) }},</p>

<p>{{ Lang::get('confide::confide.email.password_reset.body') }}</p>
<a href='{{ URL::secure('users/reset_password/'.$token) }}'> {{
	URL::secure('users/reset_password/'.$token) }} </a>

<p>{{ Lang::get('confide::confide.email.password_reset.farewell') }}</p>
