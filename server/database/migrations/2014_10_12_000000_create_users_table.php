<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password'); // No default password for security
            $table->string('phone_number')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->enum('civil_status', ['single', 'married'])->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('id_card_number')->unique()->nullable();
            $table->string('nationality')->nullable();
            $table->string('university')->nullable();
            $table->string('history')->nullable();
            $table->integer('experience_level')->nullable();
            $table->string('source')->nullable();
            $table->string('position')->nullable();
            $table->enum('grade', ['Junior', 'Mid-level', 'Senior', 'Lead', 'Principal', 'Manager'])->nullable();
            $table->date('hiring_date')->nullable(); // contract_start_date
            $table->date('contract_end_date')->nullable();
            $table->enum('type_of_contract', ['Permanent', 'Fixed-term', 'Contractor', 'Intern', 'Part-time'])->nullable();
            $table->integer('allowed_leave_days')->nullable();
            // !department_id FOREIGN KEY is added on a seperate migration file
            $table->string('image_path')->default('storage/images/default-avatar.svg');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills'); // !skills table uses the users table that's why it must be deleted first
        Schema::dropIfExists('users');
        Schema::dropIfExists('departments'); // !users table uses the departments table
    }
}
