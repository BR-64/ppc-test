<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Recreates the `p_categories` table used by the storefront `Category` model.
     * No migration shipped on this branch (the table previously came from a DB dump),
     * so this adds it from the columns the model and storefront queries reference.
     */
    public function up(): void
    {
        if (Schema::hasTable('p_categories')) {
            return;
        }

        Schema::create('p_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->string('label')->nullable();
            $table->boolean('published')->default(0);
            $table->integer('col_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_categories');
    }
};
