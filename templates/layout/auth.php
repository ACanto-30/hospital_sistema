<!doctype html>
<html lang="es">
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $this->fetch('title') ?: 'Hospital' ?></title>

  <?= $this->fetch('meta') ?>

  <!-- CSS global + específico -->
  <?= $this->Html->css(['app', 'users']) ?>
  <?= $this->fetch('css') ?>
</head>
<body class="auth">
  <?= $this->Flash->render() ?>
  <?= $this->fetch('content') ?>

  <?= $this->fetch('script') ?>
</body>
</html>
