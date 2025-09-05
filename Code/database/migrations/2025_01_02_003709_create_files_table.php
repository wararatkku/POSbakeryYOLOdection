<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.

     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id('File_ID');
            $table->string('File_name');
            $table->string('File');
            $table->string('File_Type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.

     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table){
            $table->dropSoftDeletes();

        });
        Schema::dropIfExists('files');
    }
}