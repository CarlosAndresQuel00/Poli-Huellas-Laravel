<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // Make everything about category
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('category_user', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->timestamps();
 });
        Schema::table('articls', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('restrict');
 });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('category_user');
        Schema::dropIfExists('categories');
        Schema::table('articls', function (Blueprint $table) {
            $table->dropForeign('category_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
