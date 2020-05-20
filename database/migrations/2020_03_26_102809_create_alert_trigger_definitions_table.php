<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertTriggerDefinitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_trigger_definitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('alert_trigger_id');
            $table->enum('level', ['danger', 'warning', 'info'])->default('info');
            $table->integer('rank');
            $table->boolean('deleted')->default(false);
            $table->dateTime('deleted_at')->nullable(true);
            $table->timestamps();

            $table->foreign('alert_trigger_id')
                ->references('id')
                ->on('alert_triggers')
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
        Schema::dropIfExists('alert_trigger_definitions');
    }
}
