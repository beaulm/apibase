@extends('apibase::layouts.master')

@section('content')
	@if (Session::has('flash_error'))
		<div id="flash_error">{{ Session::get('flash_error') }}</div>
	@endif
	{{ Form::open(array('id'=>'login', 'route'=>array('login'))) }}
	<table>
		<tbody>
			<tr>
				<td>{{ Form::label('email', 'Email') }}</td>
				<td>{{ Form::text('email', Input::old('email')) }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('password', 'Password') }}</td>
				<td>{{ Form::password('password') }}</td>
			</tr>
			<tr>
				<td></td>
				<td>{{ Form::submit('Login') }}</td>
			</tr>
			<tr>
				<td colspan="2"><a href="{{ URL::to('password/forgot') }}" title="Forgot your password? No worries, you can update it by following this link.">Click here to reset your password</a></td>
			</tr>
		</tbody>
	</table>
	{{ Form::close() }}
@stop