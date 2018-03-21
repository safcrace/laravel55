@extends('layout')

@section('title', "Crear Usuario")

@section('content')
    <h1>Crear Nuevo Usuario</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h6>Por Favor corrige los siguientes errores!</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('usuarios') }}">
        {{ csrf_field() }}

        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" placeholder="Peter Pan" value="{{ old('name') }}">
       {{--   @if($errors->has('name'))
            <p class="alert alert-danger">{{$errors->first('name')}}</p>
        @endif  --}}
        <br>
        <label for="email">Correo Electronico</label>
    <input type="email" name="email" id="name" placeholder="peter@example.com" value="{{ old('email') }}">
        <br>
        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">

        <button type="submit">Crear Usuario</button>
    </form>

<p>
    {{--  <a href="{{ url('/usuarios') }}">Regresar al listado de Usuario</a>  --}}
    {{--  <a href="{{ url()->previous() }}">Regresar al listado de Usuario</a>  --}}
    <a href="{{ action('UserController@index') }}">Regresar al listado de Usuario</a>
</p>
@endsection