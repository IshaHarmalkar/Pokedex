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
        Schema::create('pokemon', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->integer('pokedex_number')->unique()->index();
            $table->json('types');
            $table->json('stats');
            $table->string('sprite_url')->nullable();
            $table->text('description')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->integer('base_experience')->nullable();
            $table->timestamps();

            //Indexes for comman queries
            $table->index('name');
            $table->index('pokedex_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon');
    }
};
