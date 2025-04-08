<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('quotations', function (Blueprint $table) {
        $table->enum('policy_status', ['pending', 'generated'])->default('pending');
    });
}

public function down(): void
{
    Schema::table('quotations', function (Blueprint $table) {
        $table->dropColumn('policy_status');
    });
}

};
