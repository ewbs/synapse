<?php
$host=Config::get('app.url');
$font='font-family: Arial, Helvetica, sans-serif; color:#555; font-size:13px;';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<style type="text/css">
		* { {{$font}} }
		img { 
			max-width: 100%; 
		}
		body {
			-webkit-font-smoothing:antialiased; 
			-webkit-text-size-adjust:none; 
			width: 100%!important; 
			height: 100%;
		}
		</style>
	</head>
	<body>
		<table width="100%">
			<tr>
				<td>
					<table width="600" align="center" cellpadding="5" cellspacing="0">
						<tr>
							<td bgcolor="#2494F2" width="32"><a href="{{$host}}"><img alt="Logo Synapse" src="{{$host}}images/logo.png"/></a></td>
							<td bgcolor="#2494F2" valign="middle"><a href="{{$host}}" style="font-weight:100; font-size:19px; line-height:32px; color:#fff; text-decoration:none;"> Synapse</a></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table width="100%">
			<tr>
				<td>
					<table width="600" align="center">
						<tr>
							<td style="{{$font}}">@yield('content')</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table width="100%">
			<tr>
				<td>
					<table width="600" align="center" cellpadding="10">
						<tr>
							<td bgcolor="#F6F6F6">
								<p style="{{$font}}"><a href="{{$host}}" style="{{$font}}">Synapse</a> est développé par <a href="http://www.ensemblesimplifions.be/" style="{{$font}}">eWBS</a><br />
									<small>Propulsé par <a href="http://www.laravel.com/" style="{{$font}}">Laravel</a> | Mis en forme par <a href="http://getbootstrap.com/" style="{{$font}}">Bootstrap</a> | Version {{Config::get('app.version')}}</small>
								</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>
