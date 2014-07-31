@extends('apibase::layouts.master')

@section('content')
	@if (Session::has('flash_error'))
		<div id="flash_error">{{ Session::get('flash_error') }}</div>
	@endif
	{{ Form::open(array('id'=>'registerform', 'route'=>array('registerprocess'))) }}
	<table>
		<tbody>
			<tr>
				<td>{{ Form::label('name', 'Name') }}</td>
				<td>{{ Form::text('name', Input::old('name')) }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('email', 'Email') }}</td>
				<td>{{ Form::text('email', Input::old('email')) }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('phone', 'Phone') }}</td>
				<td>{{ Form::text('phone', Input::old('phone')) }}</td>
			</tr>
			<tr>
				<td>{{ Form::label('password', 'Password') }}</td>
				<td>{{ Form::password('password') }}</td>
			</tr>
			<tr>
				<td></td>
				<td>{{ Form::submit('Register') }}</td>
			</tr>
		</tbody>
	</table>
	{{ Form::close() }}
@stop

@section('footerScripts')
	<script type="text/javascript">
		$(document).ready(function() {
			mixpanel.track_forms("#registerform", "Register form submission");
		});
	</script>
@stop