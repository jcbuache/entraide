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
        Schema::create('person_in_needs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->timestamps();
        });

        Schema::create('weeks', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('week_number');
            $table->timestamps();
        });

        Schema::create('help_signups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('person_in_need_id')->constrained();
            $table->foreignId('week_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_signups');
        Schema::dropIfExists('weeks');
        Schema::dropIfExists('person_in_needs');
        
    }
};
