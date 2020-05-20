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
                'jobs-min-duration',
                'jobs-max-duration',
                'faulted-jobs-percentage',
                'failed-queue-items-percentage',
                'elastic-search-query'
            ])->default('none');
            $table->integer('rank');
            $table->time('time_slot_from');
            $table->time('time_slot_until');
            $table->boolean('has_relative_time_slot');
            $table->integer('relative_time_slot_duration')->nullable(true);
            $table->boolean('is_triggered_on_monday');
            $table->boolean('is_triggered_on_tuesday');
            $table->boolean('is_triggered_on_wednesday');
            $table->boolean('is_triggered_on_thursday');
            $table->boolean('is_triggered_on_friday');
            $table->boolean('is_triggered_on_saturday');
            $table->boolean('is_triggered_on_sunday');
            $table->json('parameters');
            $table->boolean('deleted')->default(false);
            $table->dateTime('deleted_at')->nullable(true);
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
