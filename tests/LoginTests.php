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
        // hit api to login with email no registered on database
        $this->post($this->url . '/login', [
            'email' => 'teste@teste.com',
            'password' => $this->defaultPassword,
        ]);

        $data = json_decode($this->response->getContent(), true);

        // confirm email doesnt exists
        $this->assertResponseStatus(404);
        $this->assertEquals('Email not registered on system', $data['message']);
    }

    public function testLoginWithoutEmailConfirmation()
    {
        // hit api to login with email not confirmed yet
        $response = $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ])->response;

        $data = json_decode($this->response->getContent(), true);

        // check if api returns a message saying to validate code received on email
        $this->assertResponseStatus(403);
        $this->assertEquals('Email not verified, new code has been sent to email', $data['message']);
    }

    public function testLoginWithWrongPassword()
    {
        // set user verified attribute true, to pass throw code validation middleware
        $this->testDatabaseCreationHelper->updateUser($this->user->id, 'verified', 1);

        // hit api trying to login with wrong password
        $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => 'abcdef',
        ]);

        $data = json_decode($this->response->getContent(), true);

        // check if api return message saying thar user sent a wrong password
        $this->assertResponseStatus(403);
        $this->assertEquals('Wrong password', $data['message']);
    }

    public function testLoginWithEmailAlreadyConfirmed()
    {
        // set user verified attribute true, to pass throw code validation middleware
        $this->testDatabaseCreationHelper->updateUser($this->user->id, 'verified', 1);

        // hit api trying to login with correct email and password
        // check if api return status 200
        $this->post($this->url . '/login', [
            'email' => $this->defaultEmail,
            'password' => $this->defaultPassword,
        ])->assertResponseOk();
    }
}
