@extends('layouts.email')
@section('content')
    <p>You can verify your email by clicking on the link below in the next 60 minutes:</p>
    <p><center><a href="{{ $routeTo }}" target="_blank">{{ $routeTo }}</a></center></p>
    <p>If you did not create an account or do not need to verify the account, please ignore the contents of this email.</p>
@endsection