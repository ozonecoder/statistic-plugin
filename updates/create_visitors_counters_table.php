<?php namespace Ozc\Statistic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateVisitorsCountersTable Migration
 */
class CreateVisitorsCountersTable extends Migration
{
    public function up()
    {
        Schema::create('ozc_statistic_visitors_counters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date');
            $table->unsignedInteger('count')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ozc_statistic_visitors_counters');
    }
}
