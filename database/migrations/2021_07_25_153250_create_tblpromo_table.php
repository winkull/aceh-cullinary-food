<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblpromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpromo', function (Blueprint $table) {
            $table->id();
            $table->string('kodepromo');
            $table->string('gambar');
            $table->text('keterangan');
            $table->dateTime('periode_awal');
            $table->dateTime('periode_akhir');
            $table->decimal('nilai_persen', 18,0);
            $table->integer('nilai_rupiah');
            $table->integer('status');
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
        Schema::dropIfExists('tblpromo');
    }
}
