<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('level')->default(1);
            $table->unsignedBigInteger('experience')->default(0);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('project_labels')->onDelete('cascade');

            $table->primary(['user_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_skills');
    }
}
