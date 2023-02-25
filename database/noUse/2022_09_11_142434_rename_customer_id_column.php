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
         Schema::table('customers', function (Blueprint $table) {
            // $table->dropForeign('reports_reporter_id_foreign');
            // $table->dropPrimary('id');
            // $table->dropForeign('customer_addresses_id_foreign');
            $table->renameColumn('id', 'user_id');
            // $table->primary('user_id')
            // ->reference('id')
            // ->on('users')
            // ->onDelete('cascade');

            // DB::statement("ALTER TABLE premtest.customers   
            // CHANGE `currentfieldname` `newfieldname` 
            // INT(10) UNSIGNED NOT NULL;");
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('customers', function (Blueprint $table) {
            // $table->dropPrimary('user_id');
            // $table->renameColumn('usre_id', 'id');
            // $table->primary('id')
            // ->reference('id')
            // ->on('users')
            // ->onDelete('cascade');
         });
    }
};
