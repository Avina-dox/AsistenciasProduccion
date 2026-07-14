<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    protected DashboardService $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        return view('dashboard', [
            'data' => $this->dashboard->obtenerDashboard(),
        ]);
    }
}