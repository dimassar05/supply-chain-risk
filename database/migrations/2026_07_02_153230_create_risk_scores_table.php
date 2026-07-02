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
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->float('weather_risk')->default(0);    // Bobot 30%
            $table->float('inflation_risk')->default(0);  // Bobot 20%
            $table->float('news_risk')->default(0);       // Bobot 40%
            $table->float('currency_risk')->default(0);   // Bobot 10%
            $table->float('total_score')->default(0);
            $table->string('risk_status');                // Low, Medium, High
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
