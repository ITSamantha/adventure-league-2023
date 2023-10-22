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
        Schema::table('neural_net_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('ir_id');

            $table->foreign('ir_id')->references('id')->on('insurance_requests')
                ->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('neural_net_requests', function (Blueprint $table) {
            $table->dropForeign(['ir_id']);

            $table->dropColumn('ir_id');
        });
    }
};
