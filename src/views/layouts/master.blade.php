<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Login</title>
		<meta name="description" content="TODO: page description"/>		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="viewport" content="initial-scale=1.0, width=device-width" />
		<meta property="og:title" content="login" /> 
		<meta property="og:url" content="http://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>"/> 
		<meta property="og:description" content="TODO: Page description" />
		<meta property="og:type" content="TODO: Test" />
		<meta property="og:site_name" content="TODO: Login" />
		<meta property="fb:admins" content="TODO: id" />
		<link rel="stylesheet" href="/css/style.css"/>
		<link rel="stylesheet" href="/css/print.css" media="print" />
		@yield('extraStylesheets')
	</head>
	<body>
		<div id="bodyContainer">
			@if(Auth::check())
				<a href="{{ URL::route('logout') }}" title="Logout">Log out</a>
			@else
				<a href="{{ URL::route('login') }}" title="Login">Log in</a>
			@endif
			<div id="content">
				@yield('content')
			</div>
		</div>
		<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
		@yield('footerScripts')
	</body>
</html>