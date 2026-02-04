<?php
declare(strict_types=1);

namespace Pay\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Pay\Model\Table\PaymentsTable;

/**
 * Pay\Model\Table\PaymentsTable Test Case
 */
class PaymentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Pay\Model\Table\PaymentsTable
     */
    protected $Payments;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'plugin.Pay.Payments',
        'plugin.Pay.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Payments') ? [] : ['className' => PaymentsTable::class];
        $this->Payments = $this->getTableLocator()->get('Payments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Payments);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \Pay\Model\Table\PaymentsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
