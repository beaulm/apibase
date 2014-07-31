@extends('apibase::layouts.master')

@section('content')
	@if (Session::has('error'))
		{{ trans(Session::get('reason')) }}
	@endif
	{{ Form::open(array('id'=>'login', 'route'=>array('remind'))) }}
		{{ Form::label('email', 'Email') }}: {{ Form::text('email') }} {{ Form::submit('Send Reminder') }}
	{{ Form::close() }}
@stop