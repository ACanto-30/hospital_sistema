<?php
declare(strict_types=1);

namespace Associates\Test\TestCase\Model\Table;

use Associates\Model\Table\MedicalRecordsTable;
use Cake\TestSuite\TestCase;


class MedicalRecordsTableTest extends TestCase
{
    
    protected $MedicalRecords;

    
    protected array $fixtures = [
    'plugin.Associates.MedicalRecords',
    'plugin.Associates.Associates',
];

    
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MedicalRecords') ? [] : ['className' => MedicalRecordsTable::class];
        $this->MedicalRecords = $this->getTableLocator()->get('MedicalRecords', $config);
    }

   
    protected function tearDown(): void
    {
        unset($this->MedicalRecords);

        parent::tearDown();
    }

    
    public function testValidationDefault(): void
{
    
    $medicalRecord = $this->MedicalRecords->newEmptyEntity();

    
    $data = [
        'associate_id' => 1,
        'doctor_id' => 1,
        'diagnosis' => 'Gripe',
        'treatment' => 'Reposo',
        'visit_date' => '2026-02-03',
    ];

    
    $medicalRecord = $this->MedicalRecords->patchEntity($medicalRecord, $data);

    
    $this->assertEmpty(
        $medicalRecord->getErrors(),
        'Se esperaban 0 errores de validación'
    );

   
    $this->assertSame(1, $medicalRecord->associate_id);
    $this->assertSame(1, $medicalRecord->doctor_id);
    $this->assertSame('Gripe', $medicalRecord->diagnosis);
}
}
