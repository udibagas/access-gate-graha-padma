<?php

use App\Models\AccessGate;
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
        Schema::create('card_readers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AccessGate::class)->references('id')->on('access_gates')->cascadeOnDelete();
            $table->string('name');
            $table->string('prefix');
            $table->enum('type', ['IN', 'OUT']);
            $table->json('camera_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_readers');
    }
};
