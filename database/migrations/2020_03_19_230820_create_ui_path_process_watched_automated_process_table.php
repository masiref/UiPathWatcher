<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUiPathProcessWatchedAutomatedProcessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ui_path_process_watched_automated_process', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ui_path_process_id');
            $table->unsignedBigInteger('watched_automated_process_id');
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
        Schema::dropIfExists('ui_path_process_watched_automated_process');
    }
}
