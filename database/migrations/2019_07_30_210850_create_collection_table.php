<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index();
            $table->string('name_sort');
            $table->string('parts')->nullable();
            $table->mediumText('overview')->nullable();
            $table->string('poster')->nullable();
            $table->string('backdrop')->nullable();
            $table->timestamps();
        });
    }
}
