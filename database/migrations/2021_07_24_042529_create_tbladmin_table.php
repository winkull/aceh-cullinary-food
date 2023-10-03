<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbladminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbladmin', function (Blueprint $table) {
            $table->string('idadmin',50);
            $table->string('namaadmin',50);
            $table->string('alamatadmin',200);
            $table->string('telpadmin',20);
            $table->string('pass',100);
            $table->id();
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
        Schema::dropIfExists('tbladmin');
    }
}
