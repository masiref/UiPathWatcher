<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiPathRobotToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_path_robot_tools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('process_name');
            $table->enum('color', [
                'none',
                'yellow',
                'success',
                'primary',
                'info',
                'link',
                'danger',
                'black',
                'light',
                'white'
            ])->default('none');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ui_path_robot_tools');
    }
}
