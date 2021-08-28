<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = ['name', 'state_id'];

    public function State(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
