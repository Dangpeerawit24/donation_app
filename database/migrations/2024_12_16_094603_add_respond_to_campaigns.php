<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('respond')->default('None')->after('price'); // เพิ่มคอลัมน์ status
        });
    }

    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('respond'); // ย้อนกลับการเพิ่มคอลัมน์
        });
    }
};
