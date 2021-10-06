<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskSprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_sprints', function (Blueprint $table) {
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('sprint_id');

            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_sprints');
    }
}
