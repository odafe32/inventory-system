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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->unsignedBigInteger('created_by');
            $table->string('sender_name');
            $table->text('sender_address');
            $table->string('sender_phone');
            $table->string('issue_from');
            $table->text('issue_from_address');
            $table->string('issue_from_phone');
            $table->string('issue_from_email');
            $table->string('issue_for');
            $table->text('issue_for_address');
            $table->string('issue_for_phone');
            $table->string('issue_for_email');
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2);
            $table->enum('status', ['Paid', 'Pending', 'Cancel'])->default('Pending');
            $table->string('logo')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users');
        });
        
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('product_name');
            $table->string('product_size')->nullable();
            $table->integer('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('product_image')->nullable();
            $table->timestamps();
            
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};