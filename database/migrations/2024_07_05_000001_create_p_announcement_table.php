<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the `p_announcement` table used by the storefront `Announcement` model.
     * The branch's `announcements` migration created a differently-named/empty stub,
     * so this adds the table the model and storefront queries actually reference.
     */
    public function up(): void
    {
        if (Schema::hasTable('p_announcement')) {
            return;
        }

        Schema::create('p_announcement', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('details')->nullable();
            $table->boolean('published')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_announcement');
    }
};
