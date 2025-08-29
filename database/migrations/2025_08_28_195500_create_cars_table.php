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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique()->index(); // ID from the API
            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('version')->nullable();
            $table->string('model_year')->nullable();
            $table->string('build_year')->nullable();
            $table->json('optionals')->nullable();
            $table->string('doors')->nullable();
            $table->string('board')->nullable();
            $table->string('chassi')->nullable();
            $table->string('transmission')->nullable();
            $table->string('km')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('url_car')->nullable();
            $table->string('old_price')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('color')->nullable();
            $table->string('fuel')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('sold')->default(false);
            $table->timestamp('created_at_source')->nullable();
            $table->timestamp('updated_at_source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};