<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFootPrintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foot_prints', function (Blueprint $table) {
            $table->id();
            $table->string('by')->nullable();
            $table->string('page')->nullable();
            $table->string('details')->nullable();
            $table->string('avatar')->nullable();
            $table->string('level')->nullable();
            $table->string('ip')->nullable();
            $table->text('browser')->nullable();
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
        Schema::dropIfExists('foot_prints');
    }
}
