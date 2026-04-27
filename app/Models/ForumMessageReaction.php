<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessageReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'forum_message_id',
        'chat_token',
        'reaction',
    ];
}
