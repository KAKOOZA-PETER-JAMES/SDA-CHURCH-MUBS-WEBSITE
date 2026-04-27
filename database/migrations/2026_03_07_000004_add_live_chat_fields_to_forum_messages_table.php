<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('chat_token', 80)->nullable()->after('ip_address');
            $table->timestamp('edited_at')->nullable()->after('updated_at');
            $table->timestamp('deleted_at')->nullable()->after('edited_at');

            $table->foreign('parent_id')->references('id')->on('forum_messages')->nullOnDelete();
            $table->index('chat_token');
            $table->index('deleted_at');
        });
    }

    public function down(): void
    {
        Schema::table('forum_messages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropIndex(['chat_token']);
            $table->dropIndex(['deleted_at']);
            $table->dropColumn(['parent_id', 'chat_token', 'edited_at', 'deleted_at']);
        });
    }
};
