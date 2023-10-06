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
            $table->bigInteger('code')->nullable();
            $table->enum('status', ['draft', 'trash ', 'published']);
            $table->timestamp('imported_t')->nullable();
            $table->text('url')->nullable();
            $table->text('creator')->nullable();
            $table->timestamp('created_t')->nullable();
            $table->timestamp('last_modified_t')->nullable();
            $table->text('product_name')->nullable();
            $table->text('quantity')->nullable();
            $table->text('brands')->nullable();
            $table->text('categories')->nullable();
            $table->text('labels')->nullable();
            $table->text('cities')->nullable();
            $table->text('purchase_places')->nullable();
            $table->text('stores')->nullable();
            $table->text('ingredients_text')->nullable();
            $table->text('traces')->nullable();
            $table->text('serving_size')->nullable();
            $table->float('serving_quantity')->nullable();
            $table->integer('nutriscore_score')->nullable();
            $table->text('nutriscore_grade')->nullable();
            $table->text('main_category')->nullable();
            $table->text('image_url')->nullable();      
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
