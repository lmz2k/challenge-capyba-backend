<?php


namespace App\Services\Hash;

use Illuminate\Support\Facades\Hash;

class HashService implements HashServiceInterface
{
    /**
     * @param $value
     * @return string
     */
    public function create($value): string
    {
        return Hash::make($value);
    }

    /**
     * @param $hash
     * @param $value
     * @return bool
     */
    public function validate($hash, $value): bool
    {
        return Hash::check($value, $hash);
    }
}
