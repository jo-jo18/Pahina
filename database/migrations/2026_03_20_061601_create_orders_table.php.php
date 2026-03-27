<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(5.00);
            $table->decimal('total', 10, 2);
            $table->enum('payment_method', ['Cash on Delivery', 'Bank Transfer']);
            $table->enum('payment_status', ['Pending', 'Awaiting Payment', 'Paid', 'Failed'])->default('Pending');
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('recipient_name');
            $table->text('delivery_address');
            $table->string('contact_number');
            $table->text('delivery_instructions')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};