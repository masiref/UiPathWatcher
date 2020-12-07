<?php

namespace App\Http\Controllers\Entity;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = Client::create($request->all());
        if ($client->save()) {
            return $client;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return $client;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $exceptions = array();
        $orchestratorApiUserPassword = $request->get('ui_path_orchestrator_api_user_password');
        if (!$orchestratorApiUserPassword) {
            array_push($exceptions, 'ui_path_orchestrator_api_user_password');
        }
        $elasticSearchApiUserUsername = $request->get('elastic_search_api_user_username');
        if ($elasticSearchApiUserUsername) {
            $elasticSearchApiUserPassword = $request->get('elastic_search_api_user_password');
            if (!$elasticSearchApiUserPassword) {
                array_push($exceptions, 'elastic_search_api_user_password');
            }
        }
        if ($client->update($request->except($exceptions))) {
            return $client;
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        if ($client->delete()) {
            return 'deleted';
        }
        return null;
    }
}
