<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{
    protected $fillable = [
        'title',
        'description',
        'salary',
        'occupation',
        'is_home_office',
        'hiring_mode',
        'city_id',
        'announcement_by',
    ];

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function Owner(): BelongsTo
    {
        return $this->belongsTo(City::class, 'announcement_by');
    }
}
