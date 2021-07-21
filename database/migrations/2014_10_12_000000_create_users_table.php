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
            $table->string('mobile',13)->unique();
            $table->string('email')->unique();
            $table->string('name',100);
            $table->string('password',100);
            $table->enum('type',\App\Models\User::TYPES)->default(\App\Models\User::TYPES_USER);
            $table->string('avatar',100)->nullable();
            $table->string('website')->nullable();
            $table->timestamp('verify_code',6)->nullable();
            $table->timestamp('verified_at')->nullable();
//            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
