<!doctype html>
<html lang="es">

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($this->fetch('title') ?: 'Hospital Privado X') ?></title>

    <?= $this->Html->css(['app', 'user-dashboard']) ?>
    <?= $this->fetch('css') ?>
</head>

<body>
    <div class="dashboard">

        <header class="dash-topbar">
            <div class="dash-brand">
                <!--<span class="dash-logo">🏥</span>-->
                <!--<span class="dash-brand-text">Hospital Privado X</span>-->
            </div>

            <!--<div class="dash-topbar-actions">
                <?= $this->Html->link(
                    'Cerrar sesión',
                    ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
                    ['class' => 'btn btn-primary btn-logout']
                ) ?>
            </div>-->
        </header>

        <main class="dash-container">
            <section class="dash-card">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </section>
        </main>

    </div>

    <?= $this->fetch('script') ?>
</body>

</html>