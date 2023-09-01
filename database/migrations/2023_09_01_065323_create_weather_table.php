<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather', function (Blueprint $table) {
            $table->id();
            $table->foreignId("city_id")->references("id")->on("cities");
            $table->string("coordinate")->nullable();
            $table->string("weather_condition")->nullable();
            $table->string("temp_celsius")->nullable();
            $table->string("temp_feels")->nullable();
            $table->string("humidity")->nullable();
            $table->string("wind_speed")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('weather');
    }
};
