<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertTriggerRuleUiPathRobotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_trigger_rule_ui_path_robot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alert_trigger_rule_id');
            $table->unsignedBigInteger('ui_path_robot_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alert_trigger_rule_ui_path_robot');
    }
}
