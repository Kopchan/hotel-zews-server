<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId  ('room_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId  ('user_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->text       ('text');
            $table->tinyInteger('grade');
            $table->boolean    ('is_moderated')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
