<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GroupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->increments("id");
            $table->string('education');
            $table->unsignedBigInteger('degree_id');
            $table->string('cv');
            $table->string('cover_letter', 250);
            $table->string('phone');
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('country_id');
            $table->timestamps();
            
            $table->index('degree_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->index('country_id');
        });

        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->timestamps();
        });

        $skills = config("dataTables.dataSkills");
        foreach ($skills as $skill) {
            DB::table('skills')->insert([
                'id' => $skill[0],
                'name' => $skill[1]
            ]);
        }

        Schema::create('profile_skills', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamps();
            
            $table->index('profile_id');
            $table->index('skill_id');
        });

        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 250);
            $table->string('name', 250);
            $table->timestamps();
        });

        $countries = config("dataTables.dataCountries");
        foreach ($countries as $country) {
            DB::table('countries')->insert([
                'id' => $country[0],
                'code' => $country[1],
                'name' => $country[2],
            ]);
        }

        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40);
            $table->timestamps();
        });

        $degrees = config("dataTables.dataDegrees");
        foreach ($degrees as $degree) {
            DB::table('degrees')->insert([
                'id' => $degree[0],
                'name' => $degree[1],
            ]);
        }
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 250);
            $table->text('content');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->index('user_id');
        });

        Schema::create('applied_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('profile_id');
            $table->unsignedInteger('job_id');
            $table->integer('save')->default(0);
            $table->integer('apply')->default(0);

            $table->unique(['profile_id', 'job_id']);
            $table->index('profile_id');
            $table->index('job_id');
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->timestamps();

            $table->unique(['sender_id', 'receiver_id']);
        });

        Schema::create('jobs_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');
            $table->integer('country_id');

            $table->unique(['job_id', 'country_id']);
        });

        Schema::create('jobs_skills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id');
            $table->integer('skill_id');

            $table->unique(['job_id', 'skill_id']);
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('content', 100);
            $table->integer('conversation_id');
            $table->timestamps();

            $table->index('user_id');
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('profile_skills');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('degrees');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('applied_jobs');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('jobs_countries');
        Schema::dropIfExists('jobs_skills');
        Schema::dropIfExists('messages');
    }
}
