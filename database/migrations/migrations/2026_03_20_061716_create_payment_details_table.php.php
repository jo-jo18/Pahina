<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('sender_bank')->nullable();
            $table->string('sender_account_name')->nullable();
            $table->string('sender_account_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('transfer_date')->nullable();
            $table->time('transfer_time')->nullable();
            $table->decimal('transfer_amount', 10, 2)->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('proof_image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_details');
    }
};