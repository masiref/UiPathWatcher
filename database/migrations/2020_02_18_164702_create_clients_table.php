<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ui_path_orchestrator_id');
            $table->string('name');
            $table->string('code');
            $table->string('ui_path_orchestrator_tenant');
            $table->string('ui_path_orchestrator_api_user_username');
            $table->string('ui_path_orchestrator_api_user_password');
            $table->string('elastic_search_url');
            $table->string('elastic_search_index');
            $table->string('elastic_search_api_user_username')->nullable(true);
            $table->string('elastic_search_api_user_password')->nullable(true);
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
        Schema::dropIfExists('clients');
    }
}
