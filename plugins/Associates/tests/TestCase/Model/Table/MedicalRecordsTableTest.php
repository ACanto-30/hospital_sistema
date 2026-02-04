<?php
declare(strict_types=1);

namespace Associates\Test\TestCase\Model\Table;

use Associates\Model\Table\MedicalRecordsTable;
use Cake\TestSuite\TestCase;

/**
 * Associates\Model\Table\MedicalRecordsTable Test Case
 */
class MedicalRecordsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Associates\Model\Table\MedicalRecordsTable
     */
    protected $MedicalRecords;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Associates.MedicalRecords',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MedicalRecords') ? [] : ['className' => MedicalRecordsTable::class];
        $this->MedicalRecords = $this->getTableLocator()->get('MedicalRecords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MedicalRecords);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \Associates\Model\Table\MedicalRecordsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
