<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'topic',
        'message',
        'attachment_path',
        'attachment_mime',
        'ip_address',
        'chat_token',
        'edited_at',
        'deleted_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function reactions()
    {
        return $this->hasMany(ForumMessageReaction::class, 'forum_message_id');
    }
}
