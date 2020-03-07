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
            $table->unsignedBigInteger('watched_automated_process_id');
            $table->unsignedBigInteger('reviewer_id')->nullable(true);
            $table->string('label');
            $table->enum('level', ['danger', 'warning', 'info'])->default('info');
            $table->boolean('closed')->default(false);
            $table->boolean('under_revision')->default(false);
            $table->dateTime('revision_started_at')->nullable(true);
            $table->dateTime('closed_at')->nullable(true);
            $table->text('closing_description')->nullable(true);
            $table->boolean('false_positive')->default(false);
            $table->boolean('ignored')->default(false);
            $table->dateTime('ignored_from')->nullable(true);
            $table->dateTime('ignored_until')->nullable(true);
            $table->text('ignorance_description')->nullable(true);
            $table->timestamps();

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
