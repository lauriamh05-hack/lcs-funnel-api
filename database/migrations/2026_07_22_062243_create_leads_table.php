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
        Schema::create('leads', function (Blueprint $table) {
            	$table->id();
        	$table->string('name');
   	        $table->string('email')->nullable();
        	$table->string('whatsapp')->nullable();
        	$table->string('source')->default('tiktok_lcs');
        	$table->string('status')->default('nouveau');
        	$table->timestamps();
    	});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
