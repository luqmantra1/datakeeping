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
    Schema::create('quotations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
        $table->string('insurance_company'); // e.g., Allianz
        $table->string('quotation_number');
        $table->decimal('amount', 12, 2)->nullable();
        $table->string('file_path')->nullable(); // to store uploaded file
        $table->enum('status', ['received', 'forwarded-to-client', 'rejected'])->default('received');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
