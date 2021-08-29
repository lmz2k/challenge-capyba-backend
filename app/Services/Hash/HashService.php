<?php


namespace App\Services\Hash;

use Illuminate\Support\Facades\Hash;

class HashService implements HashServiceInterface
{
    public function create($value): string
    {
        return Hash::make($value);
    }

    public function validate($hash, $value): bool
    {
        return Hash::check($value, $hash);
    }
}
