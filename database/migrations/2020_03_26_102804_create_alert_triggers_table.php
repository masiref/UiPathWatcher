<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_triggers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('watched_automated_process_id');
            $table->string('title');
            $table->boolean('active')->default(false);
            $table->boolean('ignored')->default(false);
            $table->dateTime('ignored_from')->nullable(true);
            $table->dateTime('ignored_until')->nullable(true);
            $table->text('ignorance_description')->nullable(true);
            $table->timestamps();

            $table->foreign('watched_automated_process_id')
                ->references('id')
                ->on('watched_automated_processes')
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
        Schema::dropIfExists('alert_triggers');
    }
}
