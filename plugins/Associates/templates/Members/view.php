<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\EntityInterface $member
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Member'), ['action' => 'edit', $member->member_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Member'), ['action' => 'delete', $member->member_id], ['confirm' => __('Are you sure you want to delete # {0}?', $member->member_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Members'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Member'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="members view content">
            <h3><?= h($member->id_card) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id Card') ?></th>
                    <td><?= h($member->id_card) ?></td>
                </tr>
                <tr>
                    <th><?= __('First Name') ?></th>
                    <td><?= h($member->first_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($member->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Phone') ?></th>
                    <td><?= h($member->phone) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($member->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Member Status') ?></th>
                    <td><?= h($member->member_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Member Id') ?></th>
                    <td><?= $this->Number->format($member->member_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Plan Id') ?></th>
                    <td><?= $this->Number->format($member->plan_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Registered At') ?></th>
                    <td><?= h($member->registered_at) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Address') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($member->address)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>