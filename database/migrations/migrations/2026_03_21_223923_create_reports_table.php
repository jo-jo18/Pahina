<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_type');
            $table->date('report_date');
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_books_sold')->default(0);
            $table->decimal('average_order_value', 10, 2)->default(0);
            $table->json('top_books')->nullable();
            $table->json('category_breakdown')->nullable();
            $table->timestamps();
            
            $table->unique(['report_type', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};