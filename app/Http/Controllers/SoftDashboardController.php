<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Farm;

class SoftDashboardController extends Controller
{
    public function index($farm_id)
    {
        $farm = Farm::findOrFail($farm_id);
        return view('soft-dashboard.index', compact('farm'));
    }
}
