<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateInterviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('jobad_id');
            $table->timestamp('start_date')->default(now());
            $table->timestamp('end_date')->default(now());
            $table->string('contact_info');
            $table->timestamps();
        });

        DB::statement(DB::raw('ALTER TABLE interviews ALTER COLUMN start_date DROP DEFAULT '));
        DB::statement(DB::raw('ALTER TABLE interviews ALTER COLUMN end_date  DROP DEFAULT '));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interviews');
    }
}
