<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCastTvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_tv', function (Blueprint $table) {
            $table->unsignedInteger('cast_id');
            $table->unsignedInteger('tv_id');
            $table->primary(['cast_id', 'tv_id']);
        });
    }
}
