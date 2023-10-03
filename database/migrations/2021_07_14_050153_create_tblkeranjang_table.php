<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblkeranjangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblkeranjang', function (Blueprint $table) {
            $table->integer('nokeranjang', 18);            
            $table->decimal('idpemesan', 18, 0);
            $table->decimal('idtoko', 18, 0);
            $table->string('kodemenu', 50);
            $table->string('namamenu', 50);
            $table->decimal('hargamenu', 18, 0);
            $table->decimal('qty', 18, 0);            
            $table->string('ket', 50);
            $table->date('tglpesan');
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
        Schema::dropIfExists('tblkeranjang');
    }
}
