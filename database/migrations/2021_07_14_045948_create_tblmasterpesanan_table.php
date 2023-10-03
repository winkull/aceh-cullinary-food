<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmasterpesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmasterpesanan', function (Blueprint $table) {
            $table->decimal('nomorpesanan', 18, 0);
            $table->string('no_faktur');
            $table->dateTime('tanggal');
            $table->decimal('idtoko', 11, 0);
            $table->decimal('idpemesan');
            $table->decimal('total_qty', 11, 0);
            $table->decimal('subtotal', 11, 0);
            $table->decimal('diskon', 11, 0);
            $table->string('kupon');
            $table->decimal('nilai_kupon', 11, 0);
            $table->integer('ongkir');
            $table->integer('grandtotal');
            $table->string('status_pesanan');
            $table->dateTime('tanggal_status');
            $table->string('status_bayar');
            $table->string('via_bayar');
            $table->integer('iddrive');
            $table->text('alasan');
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
        Schema::dropIfExists('tblmasterpesanan');
    }
}
