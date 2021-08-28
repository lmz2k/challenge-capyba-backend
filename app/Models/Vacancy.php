<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacancy extends Model
{

    const BACKEND_OCCUPATION = 'BACK';
    const FRONTEND_OCCUPATION = 'FRONT';
    const FULLSTACK_OCCUPATION = 'FULL';

    const CLT_HIRING = 'CLT';
    const PJ_HIRING= 'PJ';
    const BOTH_HIRING = 'BOTH';

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
