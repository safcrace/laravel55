<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function index()
    {
        if (request()->has('empty')) {
            $users = [];
        } else {
            $users = [
                'Joel',
                'Ellie',
                'Tess',
                'Tommy',
                'Bill'
            ];
        }
        $title = 'Listado de Usuarios';
        return view('users.index', compact('users', 'title'));
    }

    public function show($id)
    {
        return view('users.show', compact('id'));
    }

    public function create()
    {
        return 'Creando Nuevo Usuario';
    }
}
