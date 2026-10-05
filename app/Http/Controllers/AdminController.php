<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\Departamento;
use App\Models\Horario;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'totalUsuarios' => User::count(),
            'totalTurnos' => Turno::count(),
            'totalDepartamentos' => Departamento::count(),
            'totalHorarios' => Horario::count(),
        ]);
    }
}
