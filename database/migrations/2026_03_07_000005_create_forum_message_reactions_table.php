<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forum_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forum_message_id');
            $table->string('chat_token', 80);
            $table->string('reaction', 20);
            $table->timestamps();

            $table->foreign('forum_message_id')->references('id')->on('forum_messages')->cascadeOnDelete();
            $table->unique(['forum_message_id', 'chat_token', 'reaction'], 'forum_reaction_unique');
            $table->index('reaction');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_message_reactions');
    }
};
