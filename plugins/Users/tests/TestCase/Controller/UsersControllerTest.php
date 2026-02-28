<?php
declare(strict_types=1);

namespace Users\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Users\Controller\UsersController;

/**
 * Users\Controller\UsersController Test Case
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Users.Users',      // Cambiado de 'app.Users'
        'plugin.Users.Roles',      // Cambiado de 'app.Roles'
        'plugin.Users.Associates', // Asegúrate de crear este fixture
        'plugin.Users.InsurancePlans',
        'plugin.Users.Payments',
    ];

    private function loginAsAdmin(): void
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'role_id' => 1,
                    'email' => 'admin@test.com'
                ]
            ]
        ]);
    }

    private function loginAsUser(): void
    {
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 2,
                    'role_id' => 2,
                    'email' => 'user@test.com'
                ]
            ]
        ]);
    }

    public function testIndex(): void
    {
        $this->loginAsAdmin();
        $this->get('/users/users/index');
        $this->assertResponseOk();
    }

    public function testView(): void
    {
        $this->loginAsAdmin();
        $this->get('/users/users/view/1');
        $this->assertResponseOk();
    }

    public function testAdd(): void
    {
        $this->loginAsAdmin();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'email' => 'nuevo' . uniqid() . '@test.com',
            'password' => '123456',
            'role_id' => 2
        ];

        $this->post('/users/users/add', $data);
        $this->assertResponseSuccess();
    }

    public function testEdit(): void
    {
        $this->loginAsAdmin();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = ['email' => 'edit@test.com'];
        $this->post('/users/users/edit/1', $data);
        $this->assertResponseSuccess();
    }

    public function testDelete(): void
    {
        $this->loginAsAdmin();
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->post('/users/users/delete/1');
        $this->assertResponseSuccess();
    }

    public function testLogin(): void
    {
        $this->get('/users/users/login');
        $this->assertResponseOk();

        $data = [
            'email' => 'fake@test.com',
            'password' => 'wrong'
        ];
        $this->enableCsrfToken();
        $this->post('/users/users/login', $data);
        $this->assertResponseOk();
    }

    public function testLogout(): void
    {
        $this->loginAsAdmin();
        $this->get('/users/users/logout');
        $this->assertResponseSuccess();
    }

    public function testDashboard(): void
    {
        $this->loginAsAdmin();
        $this->get('/users/users/dashboard');
        $this->assertResponseOk();
    }

    public function testAdministratorDashboard(): void
    {
        $this->loginAsAdmin();
        $this->get('/users/users/administrator-dashboard');
        $this->assertResponseOk();
    }

    public function testRegister(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'email' => 'reg' . uniqid() . '@test.com',
            'password' => '123456',
            'role_id' => 2
        ];

        $this->post('/users/users/register', $data);
        $this->assertResponseSuccess();
    }

    public function testToggleUserStatus(): void
    {
        $this->loginAsAdmin();
        $this->enableCsrfToken();
        $this->post('/users/users/toggle-user-status/1');
        $this->assertResponseSuccess();
    }
}
