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
        Schema::create('evolution_chain_pokemon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evolution_chain_id')->constrained()->onDelete('cascade');
            $table->foreignId('pokemon_id')->constrained('pokemon')->onDelete('cascade');
            $table->foreignId('evolves_from_id')->nullable()->constrained('pokemon')->onDelete('set null');

            $table->unsignedTinyInteger('evolution_order')->default(1);
            $table->integer('min_level')->nullable();
            $table->string('item')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['evolution_chain_id', 'evolution_order']);
            $table->index('pokemon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evolution_chain_pokemon');
    }
};
