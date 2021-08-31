<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;


class RegisterTests extends TestCase
{
    use DatabaseTransactions;

    private $defaultName = 'Gabriel';
    private $defaultEmail = 'contact@glima.me';
    private $defaultPassword = '123mudar@@';
    private $defaultPhoto;

    private $url = '/api/auth/register';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();
        $this->defaultPhoto = UploadedFile::fake()->create('image.jpg', 1024);
    }

    public function testSuccessUserRegister()
    {
        $response = $this->call(
            'POST',
            ($this->url),
            [
                'name' => $this->defaultName,
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword
            ],
            [],
            ['photo' => $this->defaultPhoto,],
            []
        );

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(1, User::all()->count());
    }

    public function testRegisterUserWithSameEmail()
    {
        $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword
        );

        $response = $this->call(
            'POST',
            ($this->url),
            [
                'name' => $this->defaultName,
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword
            ],
            [],
            ['photo' => $this->defaultPhoto,],
            []
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testFailRegisterUserIncompleteInputs()
    {
        $this->post(($this->url), ['name' => $this->defaultName])
            ->assertResponseStatus(422);

        $this->post(($this->url), ['email' => $this->defaultEmail])
            ->assertResponseStatus(422);
        $this->post(($this->url), ['password' => $this->defaultPassword])
            ->assertResponseStatus(422);

        $this->post(($this->url), ['photo' => $this->defaultPhoto])
            ->assertResponseStatus(422);
    }

}
