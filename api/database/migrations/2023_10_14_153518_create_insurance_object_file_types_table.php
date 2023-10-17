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
        Schema::create('insurance_object_file_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_type_id')->constrained();
            $table->foreignId('file_description_id')->constrained();
            $table->foreignId('insurance_object_type_id')->constrained();
            $table->unsignedInteger('min_photo_count')->default(0);
            $table->boolean('is_editable')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurance_object_file_types');
    }
};
