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
        Schema::create('p_collections', function (Blueprint $table) {
            $table->id();
            $table->string('collection_name', 255);
            $table->string('image', 255)->nullable();
            $table->string('published', 2000)->nullable();
            $table->string('brand_name')->nullable();
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_collections');

        //
    }
};
