<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblklaimPromoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblklaim_promo', function (Blueprint $table) {
            $table->id();
            $table->integer('idpengguna');
            $table->string('kodepromo');
            $table->decimal('nilai_persen', 18,0);
            $table->integer('nilai_rupiah');
            $table->dateTime('periode_awal');
            $table->dateTime('periode_akhir');
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
        Schema::dropIfExists('tblklaim_promo');
    }
}
