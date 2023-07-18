<?php

namespace App\Models\Hrm\Support;

use App\Models\coreApp\Traits\Relationship\UserRelationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    use UserRelationTrait;

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'message'
    ];

    public function supportTicket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class);
    }
}
