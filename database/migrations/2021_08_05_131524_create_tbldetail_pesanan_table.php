<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbldetailPesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbldetail_pesanan', function (Blueprint $table) {
            $table->id();
            $table->decimal('nomorpesanan', 11, 0);
            $table->integer('kodemenu');
            $table->string('namamenu');
            $table->decimal('harga', 11, 0);
            $table->integer('qty');
            $table->decimal('subtotal');
            $table->string('keterangan');          
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
        Schema::dropIfExists('tbldetail_pesanan');
    }
}
