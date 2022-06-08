<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdColumnArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create a new field in table Articles
        Schema::table('articls', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            //$table->unsignedBigInteger('user_id'); // New field "user_id"
            //$table->foreign('user_id')->references('id')->on('users')->onDelete('restrict'); // Set the previous field like a foreign key
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Delete tha foreign key
        Schema::table('articls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
