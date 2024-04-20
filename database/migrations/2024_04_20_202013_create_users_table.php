<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('phone')->unique();
            $table->string    ('password'  , 255);
            $table->string    ('name'      , 32);
            $table->string    ('surname'   , 32);
            $table->string    ('patronymic', 32)->nullable();
            $table->boolean   ('sex');
            $table->date      ('birthday');
            $table->bigInteger('pass_number')->unique();
            $table->date      ('pass_issue_date');
            $table->string    ('pass_birth_address' , 64);
            $table->string    ('pass_authority_name', 64);
            $table->integer   ('pass_authority_code');
            $table->foreignId ('role_id')->constrained()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
