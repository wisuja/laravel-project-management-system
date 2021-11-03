<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('deadline');
            $table->unsignedInteger('order');
            $table->boolean('is_archived')->default(false);
            $table->unsignedBigInteger('task_type_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('sprint_id')->nullable();
            $table->unsignedBigInteger('status_group_id')->nullable();
            $table->unsignedBigInteger('label_id')->nullable();
            $table->unsignedBigInteger('linked_task')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('tasks')->onDelete('set null');
            $table->foreign('linked_task')->references('id')->on('tasks')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
