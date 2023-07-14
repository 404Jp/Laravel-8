<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreLiderToLiderGrupo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lider_grupo', function (Blueprint $table) {
            $table->string('nombre_lider')->nullable();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lider_grupo', function (Blueprint $table) {
            $table->dropColumn('nombre_lider');
        });
    }

}