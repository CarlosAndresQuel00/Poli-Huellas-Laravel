<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('gender', ['Macho', 'Hembra']);
            $table->enum('type', ['Perro', 'Gato', 'Otros']);
            $table->enum('size', ['Pequeño', 'Mediano', 'Grande']);
            $table->text('description');
            $table->date('date_of_birth');
            $table->boolean('adopted')->default(0);
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
        Schema::dropIfExists('pets');
    }
}
