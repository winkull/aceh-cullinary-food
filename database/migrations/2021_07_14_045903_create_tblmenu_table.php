<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblmenu', function (Blueprint $table) {
            $table->integer('kodemenu', 11);
            $table->string('namamenu', 50);
            $table->decimal('sisastock', 18, 0);
            $table->decimal('harga', 18, 0);
            $table->text('deskripsi');
            $table->string('jenis', 50);
            $table->decimal('kodetoko', 18, 0);
            $table->decimal('diskon', 18, 0);
            $table->string('persen', 100);
            $table->string('gambar');
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
        Schema::dropIfExists('tblmenu');
    }
}
