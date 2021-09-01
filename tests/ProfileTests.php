<?php


use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;

class ProfileTests extends TestCase
{
    use DatabaseTransactions;

    private string $defaultName = 'Gabriel';
    private string $defaultPassword = '123mudar@@';
    private string $defaultEmail = 'contact@glima.me';
    private $authenticatedHeader;

    private $user;

    private $profileUrl = '/api/profile';

    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $this->user = $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword,
            1
        );

        $this->login();
    }

    public function testUpdateProfileAttributes()
    {
        $newName = 'Fulano';
        $newEmail = 'email@email.com';
        $newPhoto = UploadedFile::fake()->create('image.jpg', 1024);

        $userid = $this->user->id;

        $this->post($this->profileUrl, ['name' => $newName], $this->authenticatedHeader)
            ->assertResponseOk();

        $this->post($this->profileUrl, ['email' => $newEmail], $this->authenticatedHeader)
            ->assertResponseOk();
    }

    private function login()
    {
        $this->post(
            '/api/auth/login',
            [
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword
            ]
        );

        $token = $this->response->decodeResponseJson()['token'];

        $this->authenticatedHeader = [
            'Authorization' => "Bearer $token"
        ];
    }

}
