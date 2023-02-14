<?php namespace Ozc\Statistic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateTrafficSourcesTable Migration
 */
class CreateTrafficSourcesTable extends Migration
{
    public function up()
    {
        Schema::create('ozc_statistic_traffic_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source');
            $table->unsignedInteger('count')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ozc_statistic_traffic_sources');
    }
}
