<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiPathProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_path_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ui_path_orchestrator_id');
            $table->string('name');
            $table->string('description')->nullable(true);
            $table->string('version');
            $table->bigInteger('external_id');
            $table->string('environment_name');
            $table->bigInteger('external_environment_id');
            $table->timestamps();
            
            $table->foreign('ui_path_orchestrator_id')->references('id')->on('ui_path_orchestrators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ui_path_processes');
    }
}
