@extends('apibase::layouts.master')

@section('content')
	@if (Session::has('error'))
		{{ trans(Session::get('reason')) }}
	@endif
	A password reset link has been sent to the email address you provided. Please check your email for further instructions.
@stop