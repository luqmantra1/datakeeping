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
        Schema::table('quotations', function (Blueprint $table) {
            // Check if the column doesn't exist before adding it
            if (!Schema::hasColumn('quotations', 'acceptance_status')) {
                $table->enum('acceptance_status', ['pending', 'accepted', 'rejected'])->default('pending');
            }

            if (!Schema::hasColumn('quotations', 'policy_status')) {
                $table->enum('policy_status', ['pending', 'generated'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn('acceptance_status');
            $table->dropColumn('policy_status');
        });
    }
};
