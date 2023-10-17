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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_type_id')->constrained();
            $table->string('original_path');
            $table->string('edited_path')->nullable();
            $table->timestamp('taken_at')->nullable();
            $table->foreignId('geolocation_id')->nullable()->constrained();
            $table->foreignId('insurance_request_attachment_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
