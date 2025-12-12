@extends('layouts.email')
@section('content')
    <p>You can reset your password by clicking on the link below in the next 60 minutes:</p>
    <p><center><a href="{{ $routeTo }}" target="_blank">{{ $routeTo }}</a></center></p>
    <p>If you do not need to reset your password, please ignore the contents of this email.</p>
@endsection