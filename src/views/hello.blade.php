@extends('apibase::layouts.master')

@section('content')
	Oh, hello there. Would you like to see a list of users?
	<a href="{{ URL::route('listUsers') }}" title="Users">Here, go for it</a>
@stop