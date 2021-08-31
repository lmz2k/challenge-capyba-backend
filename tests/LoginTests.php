<?php

use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;

class LoginTests extends TestCase
{
    use DatabaseTransactions;

    private $defaultName = 'Gabriel';
    private $defaultEmail = 'contact@glima.me';
    private $defaultPassword = '123mudar@@';
    private $defaultPhoto;
    private $user;

    private $url = '/api/auth';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $this->defaultPhoto = UploadedFile::fake()->create('image.jpg', 1024);

        $this->user = $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword
        );
    }

    public function testLoginWithInvalidEmail()
    {
        $this->post($this->url . '/login', [
            'email' => 'teste@teste.com',
            'password' => $this->defaultPassword,
        ]);

        $data = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(404);
        $this->assertEquals('Email not registered on system', $data['message']);
    }

    public function testLoginWithoutEmailConfirmation()
    {
        $response = $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ])->response;

        $data = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(403);
        $this->assertEquals('Email not verified, new code has been sent to email', $data['message']);
    }

    public function testLoginWithWrongPassword()
    {
        $this->testDatabaseCreationHelper->updateUser($this->user->id, 'verified', 1);

        $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => 'abcdef',
        ]);

        $data = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(403);
        $this->assertEquals('Wrong password', $data['message']);
    }

    public function testLoginWithEmailAlreadyConfirmed()
    {
        $this->testDatabaseCreationHelper->updateUser($this->user->id, 'verified', 1);

        $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ])->assertResponseOk();
    }
}
