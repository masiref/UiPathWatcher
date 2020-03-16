<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\UiPathOrchestrator;
use Illuminate\Support\Facades\Auth;

class LayoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu(Request $request, $page = 'dashboard.index')
    {
        $clients = Client::all();

        return view('layouts.menu', [
            'clients' => $clients,
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'page' => $page
        ]);
    }

    /**
     * Show the sidebar.
     *
     * @return \Illuminate\Http\Response
     */
    public function sidebar(Request $request, $page = 'dashboard.index')
    {
        $clients = Client::all();

        return view('layouts.sidebar', [
            'clients' => $clients,
            'orchestratorsCount' => UiPathOrchestrator::all()->count(),
            'page' => $page
        ]);
    }
}