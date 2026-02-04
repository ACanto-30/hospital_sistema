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
            <?= $this->Html->link(__('List Medical Records'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="medicalRecords form content">
            <?= $this->Form->create($medicalRecord) ?>
            <fieldset>
                <legend><?= __('Add Medical Record') ?></legend>
                <?php
                    echo $this->Form->control('associate_id');
                    echo $this->Form->control('doctor_id');
                    echo $this->Form->control('diagnosis');
                    echo $this->Form->control('treatment');
                    echo $this->Form->control('visit_date');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
