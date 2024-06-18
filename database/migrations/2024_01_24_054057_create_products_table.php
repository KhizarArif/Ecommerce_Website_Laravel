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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->double('price', 10, 2);
            $table->double('compare_price', 10, 2);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->nullable()->references('id')->on('sub_categories')->onDelete('cascade');
            $table->foreign('brand_id')->nullable()->references('id')->on('brands')->onDelete('cascade');
            $table->enum('is_featured', ["Yes", "No"])->default("No");
            $table->string('sku');
            $table->string('barcode')->nullable();
            $table->enum('track_qty', ["Yes", "No"])->default("No");
            $table->integer('qty')->nullable();
            $table->integer('status')->default(1);
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
