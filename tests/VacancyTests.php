<?php


use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\Helpers\TestDatabaseCreationHelper;

class VacancyTests extends TestCase
{
    use DatabaseTransactions;

    private string $defaultName = 'Gabriel';
    private string $defaultEmail = 'contact@glima.me';
    private string $defaultPassword = '123mudar@@';
    private string $title = 'Vaga de emprego';
    private string $descriptions = 'Para desenolvedor full stack';
    private int $salary = 6000;
    private int $city_id = 1;
    private bool $isHomeOffice = true;
    private string $occupation = 'FULL';
    private string $hiringMode = 'CLT';
    private $authenticatedHeader;

    private $url = '/api/vacancy';
    private TestDatabaseCreationHelper $testDatabaseCreationHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testDatabaseCreationHelper = new TestDatabaseCreationHelper();

        $this->testDatabaseCreationHelper->createTestUser(
            $this->defaultName,
            $this->defaultEmail,
            $this->defaultPassword,
            1
        );

        $this->login();
    }

    public function testCreateVacancyFieldsValidation()
    {
        // test failed cases when is missing one or more fields on body
        $this->post(
            $this->url,
            ['title' => $this->title],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'is_home_office' => $this->isHomeOffice
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => $this->occupation,
                'is_home_office' => $this->isHomeOffice
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => $this->occupation,
                'is_home_office' => $this->isHomeOffice,
                'city_id' => $this->city_id
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        // invalid city id case
        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => $this->occupation,
                'is_home_office' => $this->isHomeOffice,
                'city_id' => 10000,
                'hiring_mode' => $this->hiringMode
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        // invalid occupation case
        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => 'ABCDE',
                'is_home_office' => $this->isHomeOffice,
                'city_id' => $this->city_id,
                'hiring_mode' => $this->hiringMode
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);

        // invalid hiring mode case
        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => $this->occupation,
                'is_home_office' => $this->isHomeOffice,
                'city_id' => $this->city_id,
                'hiring_mode' => 'ABCDE'
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(422);
    }

    public function testSuccessfulVacancyCreate()
    {
        $this->post(
            $this->url,
            [
                'title' => $this->title,
                'description' => $this->descriptions,
                'salary' => $this->salary,
                'occupation' => $this->occupation,
                'is_home_office' => $this->isHomeOffice,
                'city_id' => $this->city_id,
                'hiring_mode' => $this->hiringMode
            ],
            $this->authenticatedHeader
        )->assertResponseStatus(201);
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
