<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageColumnArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articls', function (Blueprint $table) {
            $table->string('image');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articls', function (Blueprint $table) {
            $table->dropColumn('image');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}
