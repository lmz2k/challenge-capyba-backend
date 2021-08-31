<?php


use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;

class CodeTests extends TestCase
{
    use DatabaseTransactions;

    private $defaultName = 'Gabriel';
    private $defaultEmail = 'contact@glima.me';
    private $defaultPassword = '123mudar@@';
    private $user;

    private $url = '/api/auth';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $this->user = $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword
        );
    }

    public function testRefreshToken()
    {
        $tokens = [];

        for ($i = 0; $i <= 1; $i += 1) {
            $this->post(
                $this->url . '/login',
                [
                    'email' => $this->defaultEmail,
                    'password' => $this->defaultPassword,
                ]
            );

            $data = json_decode($this->response->getContent(), true);
            $tokens[] = $data['token_to_validate_code'];
        }

        $this->assertNotEquals($tokens[0], $tokens[1]);

        $this->post(
            $this->url . '/code/confirm',
            ['code' => 1234],
            ['Authorization' => 'Bearer ' . $tokens[0]]
        );

        $data = json_decode($this->response->getContent(), true);
        $this->assertEquals('Expired JWT', $data['message']);
    }

    public function testConfirmCode()
    {
        $tokens = [];

        $this->post(
            $this->url . '/login',
            [
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword,
            ]
        );

        $data = json_decode($this->response->getContent(), true);
        $token = $data['token_to_validate_code'];

        $this->post(
            $this->url . '/code/confirm',
            ['code' => 123456],
            ['Authorization' => 'Bearer ' . $token]
        );

        $this->assertResponseStatus(200);
    }
}
