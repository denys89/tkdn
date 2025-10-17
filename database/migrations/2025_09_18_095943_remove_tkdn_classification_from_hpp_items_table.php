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
        Schema::table('hpp_items', function (Blueprint $table) {
            $table->dropColumn('tkdn_classification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hpp_items', function (Blueprint $table) {
            $table->string('tkdn_classification')->nullable()->after('description');
        });
    }
};
