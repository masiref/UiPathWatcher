<?php

namespace App\Http\Controllers\Entity;

use App\Alert;
use App\AlertCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show(Alert $alert)
    {
        $alert->load('watchedAutomatedProcess.client');
        return $alert;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alert $alert)
    {
        $user = Auth::user();
        $data = $request->all();
        $action = $data['action'];
        switch ($action) {
            case 'enter_revision_mode':
                if (!$alert->under_revision) {
                    if ($alert->enterRevisionMode($user)) {
                        return $alert;
                    } else {
                        // return error: not entered in review mode
                    }
                } else {
                    // return error: alert already under revision
                }
                break;
            case 'exit_revision_mode':
                if ($alert->under_revision) {
                    if ($alert->exitRevisionMode($user)) {
                        return $alert;
                    } else {
                        // return error: not exited from review mode
                    }
                } else {
                    // return error: alert not under revision
                }
                break;
            case 'close':
                $falsePositive = $data['falsePositive'];
                $description = $data['description'];
                $categories = $this->parseCategories($data['categories']);
                if ($alert->close($falsePositive, $description, $categories)) {
                    return $alert;
                } else {
                    // return error: not closed
                }
                break;
            case 'clean':
                if ($alert->clean()) {
                    return $alert;
                } else {
                    // return error: not cleaned
                }
                break;
            case 'ignore':
                $from = $data['from_'];
                $fromTime = $data['fromTime'];
                $to = isset($data['to']) ? $data['to'] : null;
                $toTime = isset($data['toTime']) ? $data['toTime'] : null;
                $description = $data['description'];
                $categories = $this->parseCategories($data['categories']);
                if ($alert->ignore($from, $fromTime, $to, $toTime, $description, $categories)) {
                    return $alert;
                } else {
                    // return error: not ignored
                }
        }
    }

    protected function parseCategories($data)
    {
        $categories = array();
        foreach ($data as $category) {
            $id = $category['value'];
            $label = $category['text'];
            $alertCategory = AlertCategory::find($id);
            if (!$alertCategory) {
                $alertCategory = AlertCategory::create([
                    'label' => $label
                ]);
            }
            array_push($categories, $alertCategory->id);
        }

        return $categories;
    }
}
