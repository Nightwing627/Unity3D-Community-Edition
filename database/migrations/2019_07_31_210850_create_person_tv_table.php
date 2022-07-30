<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonTvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_tv', function (Blueprint $table) {
            $table->unsignedInteger('person_id');
            $table->unsignedInteger('tv_id');
            $table->primary(['person_id', 'tv_id']);
        });
    }
}
