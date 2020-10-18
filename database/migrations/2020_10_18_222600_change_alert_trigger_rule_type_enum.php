<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAlertTriggerRuleTypeEnum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE alert_trigger_rules CHANGE type type ENUM('none', 'jobs-min-duration', 'jobs-max-duration', 'faulted-jobs-percentage', 'failed-queue-items-percentage', 'elastic-search-query', 'elastic-search-multiple-queries-comparison')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE alert_trigger_rules CHANGE type type ENUM('none', 'jobs-min-duration', 'jobs-max-duration', 'faulted-jobs-percentage', 'failed-queue-items-percentage', 'elastic-search-query')");
    }
}
