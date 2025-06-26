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
        Schema::create('micropost_stamps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('micropost_id')->constrained()->onDelete('cascade');
            $table->foreignId('stamp_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // 同じユーザーが同じ投稿に複数のスタンプを押せないようにする
            $table->unique(['micropost_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('micropost_stamps');
    }
};
