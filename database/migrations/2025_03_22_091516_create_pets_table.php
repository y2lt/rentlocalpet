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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_type_id')->constrained()->onDelete('restrict');
            $table->foreignId('pet_breed_id')->nullable()->constrained()->onDelete('restrict');
            $table->string('name');
            $table->text('description');
            $table->integer('age');
            $table->enum('size', ['small', 'medium', 'large']);
            $table->decimal('daily_rate', 8, 2);
            $table->boolean('is_available')->default(true);
            $table->json('care_instructions')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
