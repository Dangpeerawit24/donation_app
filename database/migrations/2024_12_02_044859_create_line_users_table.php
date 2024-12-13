<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineUsersTable extends Migration
{
    public function up()
    {
        Schema::create('line_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->string('display_name')->nullable();
            $table->string('picture_url')->nullable();
            $table->text('status_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('line_users');
    }
}
