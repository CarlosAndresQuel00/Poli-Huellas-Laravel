<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('responsible', 100);
            $table->text('reason');
            $table->string('home', 100);
            $table->text('description');
            $table->boolean('diseases');
            $table->boolean('children');
            $table->boolean('time');
            $table->string('trip', 255);
            $table->boolean('new');
            $table->boolean('animals');
            $table->enum('state', ['Aceptado', 'Rechazado'])->default('Rechazado');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
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
        Schema::dropIfExists('forms');
    }
}
