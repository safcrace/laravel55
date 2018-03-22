<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index()
    {
        $users = User::get();

        $title = 'Listado de Usuarios';
        return view('users.index', compact('users', 'title'));
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:7'
        ], [
            'name.required' => 'El campo nombre es obligatorio' //Mensaje personalizado de error
        ]);

        /* if (empty($data['name'])) {
            return redirect('usuarios/nuevo')->withErrors([
                'name' => 'El campo es obligatorio'
            ]);
        } */

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return redirect('usuarios');
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    public function update(User $user)
    {
        if (request()->password != null) {
            $data = request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'min:7'
            ]);
        } else {
            $data = request()->validate([
                'name' => 'required',
                'email' => 'required|email',
                'password' => ''
            ]);
        }

        if ($data['password'] != null) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.show', ['user' => $user]);
    }
}
