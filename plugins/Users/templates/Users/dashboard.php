<?php
/**
 * User Dashboard – Clean Version
 * @var \Users\Model\Entity\User $user
 */
$nombreRol = isset($user->role) ? $user->role->nombre_rol : 'Usuario';
$this->assign('title', 'Dashboard - ' . $nombreRol);
?>

<div class="dashboard-page">

  <!-- Header -->
  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Dashboard de <?= h($nombreRol) ?></h2>
      <p class="dashboard-subtitle">Bienvenido, <?= h($user->nombre_completo) ?></p>
    </div>

    <div class="dashboard-meta">
      <?= $this->Html->link(
        'Cerrar Sesión',
        ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
        ['class' => 'btn btn-primary', 'style' => 'background-color: #d9534f; border-color: #d43f3a; text-decoration: none;']
      ) ?>
    </div>
  </div>

  <!-- Main Content (Placeholder) -->
  <div class="dashboard-content" style="margin-top: 40px; text-align: center; color: #666;">

    <div class="card" style="padding: 50px;">
      <h3 style="font-size: 24px; color: #ccc;">🚧</h3>
      <h3 style="margin-top: 10px;">Dashboard de <?= h($nombreRol) ?> próximamente</h3>
      <p>Estamos trabajando en las funcionalidades específicas para este rol.</p>
    </div>

  </div>

</div>