<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbltokoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbltoko', function (Blueprint $table) {
            $table->decimal('kodetoko', 18, 0)->primary();
            $table->string('nama', 50);
            $table->string('pemilik', 50);
            $table->string('alamat', 50);
            $table->string('telp', 50);
            $table->text('deskripsi');
            $table->string('email', 50);
            $table->string('kota', 50);
            $table->string('ket', 50);
            $table->decimal('rating', 18, 0);
            $table->string('kelompok', 50);
            $table->string('pass', 50);
            $table->string('longitude');
            $table->string('latitude');
            $table->string('gambar');
            $table->string('catatan');
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
        Schema::dropIfExists('tbltoko');
    }
}
