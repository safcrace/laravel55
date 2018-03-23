@extends('layout')

@section('title', 'Usuarios')

@section('content')
    <h1>{{ $title }}</h1>

<p>
    <a href="{{ route('users.create') }}">Nuevo Usuario</a>
</p>

    <ul>
        @forelse ($users as $user)
            <li>
                {{ $user->name }} - {{ $user->email }}
            {{--  <a href="{{ url("/usuarios/{$user->id}") }}">Ver detalles</a>  --}}
            {{--  <a href="{{ action('UserController@show', ['id' => $user->id]) }}">Ver detalles</a>  --}}
            <a href="{{ route('users.show', /*['id' => $user->id]*/ $user) }}">Ver detalles</a> |
            <a href="{{ route('users.edit', $user) }}">Editar</a> |
            <form action="{{ route('users.destroy', $user) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit">Eliminar</button>
            </form> |
            </li>
        @empty
            <li>No hay Usuarios Registrados</li>
        @endforelse
    </ul>
@endsection

@section('sidebar')
    @parent

    <h2>Barra lateral personalizada!</h2>
@endsection
