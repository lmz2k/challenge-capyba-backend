<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterConfirm extends Model
{
    use SoftDeletes;

    protected $fillable = ['code_hash', 'token'];
}
