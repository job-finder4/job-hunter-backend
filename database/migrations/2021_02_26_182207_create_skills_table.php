<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            NestedSet::columns($table);
            $table->timestamps();
        });
//        DB::statement('ALTER TABLE skills ADD FULLTEXT fulltext_index (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('skills',function (Blueprint $table){
//            $table->dropIndex('fulltext_index');
//        });

        Schema::dropIfExists('skills');
    }
}
