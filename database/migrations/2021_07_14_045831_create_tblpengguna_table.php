s<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblpenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblpengguna', function (Blueprint $table) {
            $table->integer('idpengguna');
            $table->string('namapengguna', 50);
            $table->string('emailpengguna', 100);
            $table->string('telp', 50);
            $table->string('wa', 50);
            $table->string('alamattinggal', 200);
            $table->string('alamatpengiriman', 200);
            $table->date('tglbergabung');
            $table->string('jambergabung', 50);
            $table->string('kodegadget', 100);
            $table->string('idverify', 50);
            $table->string('statuslogin', 50);
            $table->string('statuspengguna', 50);
            $table->string('pass', 50);
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
        Schema::dropIfExists('tblpengguna');
    }
}
