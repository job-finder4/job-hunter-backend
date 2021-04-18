<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->string('title');
            $table->text('description');
            $table->unsignedMediumInteger('min_salary');
            $table->unsignedMediumInteger('max_salary');
            $table->string('job_type');
            $table->string('job_time');
            $table->string('location');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expiration_date');
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
        Schema::dropIfExists('jobads');
    }
}
