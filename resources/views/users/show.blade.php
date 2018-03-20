@extends('layout')

@section('title', "Usuario {$user->id}")

@section('content')
    <h1>Usuario #{{ $user->id }}</h1>

<p>Nombre del Usuario: {{ $user->name }}</p>
<p>Correo Electrónico: {{ $user->email }}</p>

<p>
    {{--  <a href="{{ url('/usuarios') }}">Regresar al listado de Usuario</a>  --}}
    {{--  <a href="{{ url()->previous() }}">Regresar al listado de Usuario</a>  --}}
    <a href="{{ action('UserController@index') }}">Regresar al listado de Usuario</a>
</p>
@endsection