<?php

namespace App\Models;

use App\Support\Catalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inquiry extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'topic', 'reference', 'message',
        'status', 'notes', 'ip', 'customer_id', 'reply', 'replied_at',
        'forward_consent', 'forwarded_to', 'forwarded_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
            'forward_consent' => 'boolean',
            'forwarded_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function statusLabel(): string
    {
        return Catalog::label(Catalog::INQUIRY_STATUSES, $this->status);
    }

    public function topicLabel(): string
    {
        return Catalog::label(Catalog::INQUIRY_TOPICS, $this->topic);
    }
}
