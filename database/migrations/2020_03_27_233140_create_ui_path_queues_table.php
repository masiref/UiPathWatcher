<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiPathQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_path_queues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ui_path_orchestrator_id');
            $table->string('name');
            $table->string('description')->nullable(true);
            $table->bigInteger('external_id');
            $table->timestamps();
            
            $table->foreign('ui_path_orchestrator_id')
                ->references('id')
                ->on('ui_path_orchestrators')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ui_path_queues');
    }
}
