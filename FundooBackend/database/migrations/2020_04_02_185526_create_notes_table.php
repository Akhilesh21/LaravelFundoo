<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('userid'); 
            $table->string('title')->nullable();
            $table->string('labelname')->nullable();
            $table->string('decription')->nullable();
            $table->boolean('ispinned')->default(0);
            $table->boolean('istrash')->default(0);
            $table->boolean('isarchive')->default(0);
            $table->string("reminder")->nullable();
            $table->string("color")->nullable();
            $table->string("collaborator")->nullable();
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
        Schema::dropIfExists('notes');
    }
}
