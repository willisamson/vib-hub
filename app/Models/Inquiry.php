<?php

namespace App\Models;

use App\Support\Catalog;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = [
        'name', 'company', 'email', 'phone', 'topic', 'reference', 'message',
        'status', 'notes', 'ip',
    ];

    public function topicLabel(): string
    {
        return Catalog::label(Catalog::INQUIRY_TOPICS, $this->topic);
    }
}
