<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblpromokhususTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpromokhusus', function (Blueprint $table) {
            $table->id();
            $table->string('kodepromo');
            $table->string('gambar');
            $table->text('keterangan');
            $table->dateTime('periode_awal');
            $table->dateTime('periode_akhir');
            $table->integer('idpengguna');
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
        Schema::dropIfExists('tblpromokhusus');
    }
}
