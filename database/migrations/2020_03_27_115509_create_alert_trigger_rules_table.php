<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertTriggerRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_trigger_rules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alert_trigger_definition_id');
            $table->enum('type', [
                'none',
                'jobs-duration',
                'faulted-jobs-percentage',
                'failed-queue-items-percentage',
                'elastic-search-query'
            ])->default('none');
            $table->integer('rank');
            $table->boolean('relative_time_slot');
            $table->integer('relative_time_slot_duration');
            $table->dateTime('time_slot_from');
            $table->dateTime('time_slot_until');
            $table->json('failed_queue_items_percentage');
            $table->json('faulted_jobs_percentage');
            $table->json('jobs_durations');
            $table->json('kibana_metric_visualization');
            $table->json('kibana_search');
            $table->timestamps();

            $table->foreign('alert_trigger_definition_id')
                ->references('id')
                ->on('alert_trigger_definitions')
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
        Schema::dropIfExists('alert_trigger_rules');
    }
}
