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
        Schema::create('insurance_request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurance_request_id')->constrained();
            $table->foreignId('ioft_id')->constrained('insurance_object_file_types', 'id');
            $table->foreignId('attachment_status_id')->constrained();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_request_attachments');
    }
};
