<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::latest()
            ->paginate(10);

        return view(
            'usuarios.index',
            compact('usuarios')
        );
    }

public function create()
{
    $usuarios = User::orderBy('name')->get();

    $roles = Role::orderBy('name')->get();

    return view(
        'usuarios.create',
        compact(
            'usuarios',
            'roles'
        )
    );
}
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt(
                $request->password
            )
        ]);

        $user->assignRole(
            $request->roles
        );

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario creado correctamente'
            );
    }

    public function edit(User $usuario)
    {
        $roles = Role::all();

        return view(
            'usuarios.edit',
            compact(
                'usuario',
                'roles'
            )
        );
    }

    public function update(
    Request $request,
    User $usuario
) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $usuario->id,
        'roles' => 'required|array|min:1',
        'roles.*' => 'exists:roles,name',
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email
    ];

    if ($request->filled('password')) {

        $data['password'] = bcrypt(
            $request->password
        );

    }

    $usuario->update($data);

    $usuario->syncRoles(
        $request->roles
    );

    return redirect()
        ->route('usuarios.index')
        ->with(
            'success',
            'Usuario actualizado correctamente'
        );
}
    public function destroy(User $usuario)
    {
        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario eliminado correctamente'
            );
    }
}