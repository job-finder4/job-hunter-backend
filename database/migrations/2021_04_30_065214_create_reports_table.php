<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reportable_id')->unsigned();
            $table->string('reportable_type');
            $table->string('description');
            $table->unsignedBigInteger('reporter_id');
            $table->boolean('is_read')->default(0);
            $table->timestamps();
//            $table->unique(['reportable_id','reportable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
