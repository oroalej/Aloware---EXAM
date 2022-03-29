<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'message',
        'depth'
    ];

    public function children(): HasMany
    {
        return $this->HasMany( __CLASS__, 'parent_id' );
    }

    public function nestedChildren(): HasMany
    {
        return $this->HasMany( __CLASS__, 'parent_id' )
            ->with( 'nestedChildren' )
            ->latest();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo( __CLASS__, 'parent_id' );
    }
}
