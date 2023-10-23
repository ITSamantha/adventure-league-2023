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
        Schema::create('neural_net_request_statuses', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->timestamps();
        });

        Schema::create('neural_net_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('status_id');

            $table->foreign('status_id')->references('id')->on('neural_net_request_statuses')
                ->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('neural_net_requests');
        Schema::dropIfExists('neural_net_request_statuses');
    }
};
