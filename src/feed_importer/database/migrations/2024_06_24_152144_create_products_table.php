<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('entity_id');
        $table->string('category_name')->nullable();
        $table->string('sku')->nullable();
        $table->string('name')->nullable();
        $table->text('description')->nullable();
        $table->text('shortdesc')->nullable();
        $table->integer('price')->nullable(); // Prices are multiplied by a floating point multiplier and stored as integer to avoid floating point rounding issues
        $table->string('link')->nullable();
        $table->string('image')->nullable();
        $table->string('brand')->nullable();
        $table->integer('rating')->nullable();
        $table->string('caffeine_type')->nullable();
        $table->integer('count')->nullable();
        $table->boolean('flavored')->nullable();
        $table->boolean('seasonal')->nullable();
        $table->boolean('instock')->nullable();
        $table->boolean('facebook')->nullable();
        $table->boolean('is_kcup')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
