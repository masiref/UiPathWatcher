<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchedAutomatedProcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watched_automated_processes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id');
            $table->string('code');
            $table->string('name');
            $table->string('operational_handbook_page_url')->nullable(true);
            $table->string('kibana_dashboard_url')->nullable(true);
            $table->text('additional_information')->nullable(true);
            $table->boolean('running_period_monday');
            $table->boolean('running_period_tuesday');
            $table->boolean('running_period_wednesday');
            $table->boolean('running_period_thursday');
            $table->boolean('running_period_friday');
            $table->boolean('running_period_saturday');
            $table->boolean('running_period_sunday');
            $table->time('running_period_time_from');
            $table->time('running_period_time_until');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('watched_automated_processes');
    }
}
