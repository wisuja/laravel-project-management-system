<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('task_type_id')->references('id')->on('task_types')->onDelete('cascade');
            $table->foreign('sprint_id')->references('id')->on('sprints')->onDelete('set null');
            $table->foreign('status_group_id')->references('id')->on('project_status_groups')->onDelete('set null');
            $table->foreign('label_id')->references('skill_id')->on('project_labels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
}
