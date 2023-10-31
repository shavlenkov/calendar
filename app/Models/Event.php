<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'resourceId',
        'format',
        'start',
        'end'
    ];

    public function room() {
        return $this->belongsTo(Event::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,  'event_users', 'event_id', 'user_id');
    }
}
