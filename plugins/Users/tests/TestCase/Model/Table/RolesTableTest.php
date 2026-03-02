<?php
declare(strict_types=1);

namespace Users\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Users\Model\Table\RolesTable;

/**
 * Users\Model\Table\RolesTable Test Case
 */
class RolesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Users\Model\Table\RolesTable
     */
    protected $Roles;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Users.Roles',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Roles') ? [] : ['className' => RolesTable::class];
        $this->Roles = $this->getTableLocator()->get('Roles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Roles);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $validator = $this->Roles->getValidator('default');

        // Test missing name
        $errors = $validator->validate(['description' => 'Only desc']);
        $this->assertArrayHasKey('name', $errors);

        // Test valid data
        $data = [
            'name' => 'Support',
            'description' => 'Support role',
        ];
        $errors = $validator->validate($data);
        $this->assertEmpty($errors);
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules(): void
    {
        $role = $this->Roles->newEntity([
            'name' => 'Test Role',
            'description' => 'Test description',
            'access_level' => 1,
            'active' => 1,
        ]);
        $success = $this->Roles->save($role);
        $this->assertNotFalse($success);
    }

}
