<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('policies', function (Blueprint $table) {
        $table->enum('status', ['proposal', 'quotation', 'accepted', 'policy_generated'])->default('proposal');
    });
}

public function down()
{
    Schema::table('policies', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
