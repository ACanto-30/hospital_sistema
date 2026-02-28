<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->set('title', $this->fetch('title'));
?>
<!doctype html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($this->fetch('title')) ?></title>
</head>
<body>
    <?= $this->fetch('content') ?>
</body>
</html>