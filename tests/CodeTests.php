<?php


use App\Models\RegisterConfirm;
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

        // try to login twice to generate two different codes and token
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

        // check if has created two different tokens
        $this->assertNotEquals($tokens[0], $tokens[1]);

        // check if only the last token is still on databa
        $this->assertEquals(1, RegisterConfirm::all()->count());

        // try to confirm with old token
        $this->post(
            $this->url . '/code/confirm',
            ['code' => 1234],
            ['Authorization' => 'Bearer ' . $tokens[0]]
        );

        $data = json_decode($this->response->getContent(), true);

        // check if returns message confirming that code is really no more available
        $this->assertEquals('Expired JWT', $data['message']);
    }

    public function testConfirmCode()
    {
        $tokens = [];

        // try to login with unconfirmed user
        $this->post(
            $this->url . '/login',
            [
                'email' => $this->defaultEmail,
                'password' => $this->defaultPassword,
            ]
        );

        // getting token to send as Authorization when confirm code sent to email
        $data = json_decode($this->response->getContent(), true);
        $token = $data['token_to_validate_code'];

        // hit api to confirm email
        $this->post(
            $this->url . '/code/confirm',
            ['code' => 123456],
            ['Authorization' => 'Bearer ' . $token]
        );

        // check if everything happens correctly
        $this->assertResponseStatus(200);

        // hit api trying to send a new code to email
        $this->post(
            $this->url . '/code/resend',
            ['email' => $this->defaultEmail]
        );

        // getting token to send as Authorization when confirm code sent to email
        $data = json_decode($this->response->getContent(), true);

        // check if returns message confirming that email has been already confirmed before
        $this->assertEquals('Email already verified on system', $data['message']);

    }
}
