@extends('layouts.master')

@section('content')
	@if (Session::has('error'))
		{{ trans(Session::get('reason')) }}
	@endif
	{{ Form::open(array('id'=>'login', 'route'=>array('reset', $token))) }}
	<table>
		<tbody>
			<tr>
				<td>{{ Form::label('email', 'Email') }}</td>
				<td>{{ Form::text('email', Input::get('email')) }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('password', 'Password') }}</td>
				<td>{{ Form::password('password') }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('password_confirmation', 'Confirm Password') }}</td>
				<td>{{ Form::password('password_confirmation') }}</td>
			</tr>
			<tr>
				<td></td>
				<td>{{ Form::submit('Submit') }}</td>
			</tr>
		</tbody>
	</table>
	{{ Form::hidden('token', $token) }}
	{{ Form::close() }}
@stop