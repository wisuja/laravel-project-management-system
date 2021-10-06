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
            $table->string('title');
            $table->text('description');
            $table->date('deadline');
            $table->unsignedTinyInteger('status');
            $table->unsignedBigInteger('label')->nullable();
            $table->unsignedBigInteger('linked_task')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('tasks')->onDelete('set null');
            $table->foreign('linked_task')->references('id')->on('tasks')->onDelete('set null');
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
