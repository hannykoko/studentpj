<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id')->length('11')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('father_name')->nullable();
            $table->string('nrc_number',40)->nullable()->default("");
            $table->string('phone_no',30)->nullable()->default("");
            $table->string('email')->nullable(false);
            $table->tinyInteger('gender')->length('3')->comment('1=>Male,2=>Female')->nullable(false);
            $table->date('date_of_birth')->nullable();
            $table->string('avatar')->nullable()->default("");
            $table->string('address')->length(500)->nullable();
            $table->string('career_path')->length('3')->comment('1=>Front End,2=>Back End')->nullable()->default(1);
            $table->softDeletes();
            $table->integer('created_emp')->length('11')->nullable(false);
            $table->integer('updated_emp')->length('11')->nullable(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
