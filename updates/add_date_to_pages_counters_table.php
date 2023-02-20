<?php namespace Ozc\Statistic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * AddDateToPagesCountersTable Migration
 */
class AddDateToPagesCountersTable extends Migration
{
    public function up()
    {
        Schema::table('ozc_statistic_pages_counters', function (Blueprint $table) {
            $table->string('date')->after('page');
        });
    }

    public function down()
    {
        Schema::table('ozc_statistic_pages_counters', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
}
