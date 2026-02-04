<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $medicalRecord
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Medical Record'), ['action' => 'edit', $medicalRecord->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Medical Record'), ['action' => 'delete', $medicalRecord->id], ['confirm' => __('Are you sure you want to delete # {0}?', $medicalRecord->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Medical Records'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Medical Record'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="medicalRecords view content">
            <h3><?= h($medicalRecord->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($medicalRecord->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Associate Id') ?></th>
                    <td><?= $this->Number->format($medicalRecord->associate_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Doctor Id') ?></th>
                    <td><?= $this->Number->format($medicalRecord->doctor_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Visit Date') ?></th>
                    <td><?= h($medicalRecord->visit_date) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Diagnosis') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($medicalRecord->diagnosis)); ?>
                </blockquote>
            </div>
            <div class="text">
                <strong><?= __('Treatment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($medicalRecord->treatment)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>