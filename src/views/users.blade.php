@extends('apibase::layouts.master')

@section('content')
	<h1>Users</h1>

	@foreach($users as $user)
		<li>{{ $user->name }}</li>
	@endforeach
	</ul>
@stop