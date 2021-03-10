<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->unsignedBigInteger('user_id');
<<<<<<< HEAD:database/migrations/2021_03_04_175051_create_cvs_table.php
=======
            $table->string('details')->nullable();
            $table->boolean('visible')->default(true);
>>>>>>> 73806b1eeab30e147265a7f491dc5eceac1520bb:database/migrations/2021_03_03_174056_create_profiles_table.php
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
        Schema::dropIfExists('cvs');
    }
}
