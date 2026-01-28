<!doctype html>
<html lang="es">
<head>
  <?= $this->Html->charset() ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $this->fetch('title') ?: 'Dashboard' ?></title>

  <!-- CSS global + dashboard -->
  <?= $this->Html->css(['app', 'user-dashboard']) ?>
  <?= $this->fetch('css') ?>
</head>
<body class="dashboard">

  <?= $this->Flash->render() ?>
  <?= $this->fetch('content') ?>

  <?= $this->fetch('script') ?>
</body>
</html>
