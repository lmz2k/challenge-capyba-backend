<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TokenTacking extends Model
{
    use SoftDeletes;
    protected $table = 'token_tracking';
    protected $fillable = ['token'];
}
