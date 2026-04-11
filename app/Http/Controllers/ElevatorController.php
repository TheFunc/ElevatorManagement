<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ElevatorController extends Controller
{
    // 系统管理页面
    public function ledger()
    {
        return view('elevator.ledger');
    }

    public function maintenance()
    {
        return view('elevator.maintenance');
    }

    public function warning()
    {
        return view('elevator.warning');
    }

    // 资料管理页面
    public function device()
    {
        return view('data.device');
    }

    public function prepare()
    {
        return view('data.prepare');
    }

    public function maintenanceData()
    {
        return view('data.maintenance');
    }

    public function inspection()
    {
        return view('data.inspection');
    }

    public function fault()
    {
        return view('data.fault');
    }

    public function repair()
    {
        return view('data.repair');
    }

    public function accident()
    {
        return view('data.accident');
    }

    public function rescue()
    {
        return view('data.rescue');
    }

    public function query()
    {
        return view('data.query');
    }
}
