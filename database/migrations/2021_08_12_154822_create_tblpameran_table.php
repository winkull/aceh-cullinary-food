<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblpameranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpameran', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->text('gambar');
            $table->string('linkurl', 200);
            $table->string('status1', 20);
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
        Schema::dropIfExists('tblpameran');
    }
}
