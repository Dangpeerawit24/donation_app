<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaign_transactions', function (Blueprint $table) {
            $table->id();            
            $table->string('transactionID');
            $table->string('campaignsid');
            $table->string('campaignsname');
            $table->string('lineId');
            $table->string('lineName');
            $table->string('form');
            $table->string('value');
            $table->text('details');
            $table->text('details2');
            $table->text('detailsbirthday');
            $table->text('detailstext');
            $table->string('evidence');
            $table->string('url_img');
            $table->string('qr_url');
            $table->string('status');
            $table->string('notify');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_transactions');
    }
};
