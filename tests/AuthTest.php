<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->setUp();
        parent::__construct($name, $data, $dataName);
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testRegisterUser()
    {
        dd(\App\Models\User::all());
    }
}
