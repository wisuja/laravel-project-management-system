<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.user.home');
    }

    public function store () {
        $data = Project::all();

        return DataTables::of($data)
                            ->addIndexColumn()
                            ->make(true);
    }
}
