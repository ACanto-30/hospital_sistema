<?php
declare(strict_types=1);

namespace Associates\Test\TestCase\Model\Table;

use Associates\Model\Table\MembersTable;
use Cake\TestSuite\TestCase;

/**
 * Associates\Model\Table\MembersTable Test Case
 */
class MembersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Associates\Model\Table\MembersTable
     */
    protected $Members;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Associates.Members',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Members') ? [] : ['className' => MembersTable::class];
        $this->Members = $this->getTableLocator()->get('Members', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Members);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \Associates\Model\Table\MembersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
