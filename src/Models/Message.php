<?php

namespace Binomedev\Contact\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'contact_inbox';

    protected $fillable = [
        'subscriber_id',
        'to',
        'from',
        'subject',
        'content',
        'mailable',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
