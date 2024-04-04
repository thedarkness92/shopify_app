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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name');
            $table->double('price');
            $table->string('interval')->nullable();
            $table->double('capped_amount')->nullable();
            $table->string('terms')->nullable();
            $table->integer('trial_days')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('test')->default(0);
            $table->tinyInteger('on_install')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
