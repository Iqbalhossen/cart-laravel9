@extends('master')
@section('assignment')

    <div class="container">
        <h2>Full Name: {{$data->name}}</h2>
        <h5>Email: {{$data->email}}</h5>
    </div>

    <a href="{{route('change.profile')}}" class="btn btn-primary">Change Profile</a>
@endsection