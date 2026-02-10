<?php
declare(strict_types=1);

use Migrations\BaseSeed;

/**
 * Conditions seed.
 */
class ConditionsSeed extends BaseSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/migrations/4/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            [
                'condition' => 'Diabetes',
            ],
            [
                'condition' => 'Hipertensión',
            ],
            [
                'condition' => 'Asma',
            ],
            [
                'condition' => 'Artritis',
            ],
            [
                'condition' => 'Cáncer',
            ],
            [
                'condition' => 'Enfermedad Cardíaca',
            ],
            [
                'condition' => 'Enfermedad Renal',
            ],
            [
                'condition' => 'Enfermedad Hepática',
            ],
            [
                'condition' => 'Enfermedad Pulmonar',
            ],
            [
                'condition' => 'Enfermedad Neurológica',
            ],
        ];

        $table = $this->table('conditions');
        $table->insert($data)->save();
    }
}
