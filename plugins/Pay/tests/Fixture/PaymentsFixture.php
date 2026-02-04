<?php
declare(strict_types=1);

namespace Pay\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PaymentsFixture
 */
class PaymentsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'associate_id' => 1,
                'processed_by_user_id' => 1,
                'payment_method_id' => 1,
                'payment_status_id' => 1,
                'amount' => 1.5,
                'proof_image' => 'Lorem ipsum dolor sit amet',
                'payment_date' => '2026-02-03 06:47:08',
            ],
        ];
        parent::init();
    }
}
