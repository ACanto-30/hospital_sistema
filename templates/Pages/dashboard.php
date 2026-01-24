<div class="dashboard">
    <h1>
        Bienvenido
        <?= isset($user->name) ? h($user->name) : 'Usuario' ?>
    </h1>

    <?php if (isset($user->role)) : ?>
        <p>Rol: <?= h($user->role) ?></p>
    <?php else : ?>
        <p class="role-error">Rol no disponible</p>
    <?php endif; ?>
</div>

