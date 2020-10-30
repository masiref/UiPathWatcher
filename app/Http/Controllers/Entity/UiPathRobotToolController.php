<?php

namespace App\Http\Controllers\Entity;

use App\Http\Controllers\Controller;
use App\UiPathRobotTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UiPathRobotToolController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$request['api_user_password'] = Crypt::encryptString($request['api_user_password']);
        $robotTool = UiPathRobotTool::create($request->all());
        if ($robotTool->save()) {
            return $robotTool;
        }
        return null;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UiPathRobotTool  $uiPathRobotTool
     * @return \Illuminate\Http\Response
     */
    public function show(UiPathRobotTool $uiPathRobotTool)
    {
        return $uiPathRobotTool;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UiPathRobotTool  $uiPathRobotTool
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UiPathRobotTool $uiPathRobotTool)
    {
        if ($uiPathRobotTool->update($request->all())) {
            return $uiPathRobotTool;
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UiPathRobotTool  $uiPathRobotTool
     * @return \Illuminate\Http\Response
     */
    public function destroy(UiPathRobotTool $uiPathRobotTool)
    {
        if ($uiPathRobotTool->delete()) {
            return 'deleted';
        }
        return null;
    }
}
