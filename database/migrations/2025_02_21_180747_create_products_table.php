<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('tag_number')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('brand');
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('gender');
            $table->text('description');
            $table->integer('stock');
            $table->string('size')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->decimal('tax', 5, 2)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};