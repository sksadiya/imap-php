<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('menu_items',function(Blueprint $table) {
        $table->unsignedBigInteger('page_id');
        $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('menu_items', function(Blueprint $table) {
        $table->dropColumn('page_id');
       });
    }
};
