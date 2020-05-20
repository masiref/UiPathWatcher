<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable(true);
            $table->unsignedBigInteger('alert_trigger_id');
            $table->unsignedBigInteger('alert_trigger_definition_id');
            $table->unsignedBigInteger('watched_automated_process_id');
            $table->unsignedBigInteger('reviewer_id')->nullable(true);
            $table->boolean('closed')->default(false);
            $table->boolean('ignored')->default(false);
            $table->boolean('under_revision')->default(false);
            $table->dateTime('revision_started_at')->nullable(true);
            $table->dateTime('closed_at')->nullable(true);
            $table->text('closing_description')->nullable(true);
            $table->boolean('false_positive')->default(false);
            $table->json('messages')->nullable(true);
            $table->boolean('auto_closed')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('alerts')
                ->onDelete('cascade');

            $table->foreign('watched_automated_process_id')
                ->references('id')
                ->on('watched_automated_processes')
                ->onDelete('cascade');
            
            $table->foreign('reviewer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alerts');
    }
}
