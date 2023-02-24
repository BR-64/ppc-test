<?php

use App\Models\User;

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
        Schema::create('p_products', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 255);
            $table->string('form', 5);
            $table->string('glaze', 5);
            $table->string('bz', 10);
            $table->string('technique', 10);
            $table->string('collection', 50)->nullable();
            $table->string('category', 50);
            $table->string('type', 50)->nullable();
            $table->string('brand_name', 50)->nullable();
            $table->string('product_description', 500)->nullable();
            $table->string('color', 50);
            $table->string('finish', 50);
            $table->string('tag1', 50)->nullable();
            $table->string('tag2', 50)->nullable();
            $table->string('tag3', 50)->nullable();
            $table->tinyInteger('pre_order', 1)->nullable();
            $table->tinyInteger('promotion', 1)->nullable();
            $table->double('discount', 8,2)->nullable();
            $table->integer('weight_g', 11);
            $table->double('retail_price', 8,2);
            $table->integer('width', 11);
            $table->integer('length', 11);
            $table->integer('height', 11);
            $table->string('pic_enpro', 255);
            $table->string('wlh', 255);

            $table->integer('pcategory_id', 10)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->tinyInteger('published',1);
            $table->tinyInteger('newp',1);
            $table->tinyInteger('hilight',1);
            $table->string('image',2000);


            $table->string('image', 2000)->nullable();

            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
            $table->timestamps();
        //
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_products');
        //

    }
};
