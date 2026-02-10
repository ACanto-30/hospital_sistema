<?php
/**
 * User Dashboard – Life Insurance (Diseño Armónico con Header Verde Segmentado)
 */
$this->assign('title', 'Dashboard de Usuario');
?>

<?php $this->append('css'); ?>
<style>
:root{
  --verde-oscuro:#0b6b3a;
  --verde:#1b8a5a;
  --verde-claro:#e6f6ee;
  --verde-blanco:#f3f8f5;
  --amarillo:#f2c200;
  --amarillo-suave:#fff4cc;
  --gris:#555;
}

/* Fondo general */
.dashboard{
  margin:0;
  min-height:100vh;
  background:linear-gradient(135deg,#0f6b3f 0%,#1b8a5a 70%) !important;
  font-family:system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
}

/* HEADER VERDE */
.dashboard-header{
  background:linear-gradient(135deg,#0f6b3f,#1b8a5a);
  padding:40px 0 90px;
}

/* Header segmentado */
.dashboard-header-inner{
  max-width:1200px;
  margin:0 auto;
  padding:0 32px;
  display:grid;
  grid-template-columns: 1fr 1.4fr auto;
  gap:18px;
  align-items:center;
}

/* Bloques del header */
.header-box{
  background:rgba(255,255,255,0.14);
  padding:20px 24px;
  border-radius:18px;
  box-shadow:0 8px 18px rgba(0,0,0,.18);
  backdrop-filter:blur(6px);
}

/* Textos header */
.header-box h2{
  margin:0;
  color:#fff;
  font-weight:700;
}

.header-box p{
  margin:4px 0 0;
  color:#d9efe4;
  font-size:.95rem;
}

/* Contenedor principal */
.dashboard-page{
  max-width:1200px;
  margin:-70px auto 40px;
  background:var(--verde-blanco);
  border-radius:20px;
  padding:32px;
  box-shadow:0 25px 50px rgba(0,0,0,.18);
}

/* Botón logout */
.btn-logout{
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:10px 22px;
  border-radius:12px;
  font-weight:600;
  text-decoration:none;
  box-shadow:0 6px 14px rgba(0,0,0,.18);
}

/* Summary cards */
.summary-card{
  flex:1;
  min-width:200px;
  padding:22px;
  border-radius:14px;
  box-shadow:0 8px 18px rgba(0,0,0,.08);
}

.summary-green{
  background:var(--verde-claro);
  border-left:6px solid var(--verde);
}

.summary-yellow{
  background:var(--amarillo-suave);
  border-left:6px solid var(--amarillo);
}

/* Cards generales */
.card{
  background:#ffffffcc;
  padding:22px;
  border-radius:14px;
  box-shadow:0 8px 18px rgba(0,0,0,.08);
  backdrop-filter:blur(4px);
}

/* Badge estado */
.badge-status{
  background:var(--verde);
  color:#fff;
  padding:6px 14px;
  border-radius:20px;
  font-size:.85rem;
  font-weight:600;
}

/* Progreso */
.progress-bar{
  background:#e0e0e0;
  border-radius:12px;
  height:12px;
  overflow:hidden;
}

.progress-bar span{
  display:block;
  height:100%;
  width:100%;
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
}

/* Botón pagar */
.btn-pay{
  display:block;
  text-align:center;
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:12px;
  border-radius:10px;
  margin-top:12px;
  text-decoration:none;
  font-weight:700;
  box-shadow:0 6px 16px rgba(0,0,0,.2);
}

/* Listas */
.benefits li{
  list-style:none;
  margin-bottom:6px;
}

.benefits li::before{
  content:"✔";
  color:var(--verde);
  font-weight:700;
  margin-right:6px;
}
</style>
<?php $this->end(); ?>

<div class="dashboard-page">

  <!-- Header -->
  <div class="dashboard-header" style="display:flex; justify-content: space-between; align-items:center; margin-bottom:20px;">
    <div>
      <h2 class="dashboard-title" style="margin:0; font-size:1.8rem; font-weight:600;">Panel de Control</h2>
      <p class="dashboard-subtitle" style="margin:4px 0 0; color:#555;">
        Bienvenido, <?= h(($associate->first_name ?? 'Usuario') . ' ' . ($associate->last_name ?? '')) ?>
      </p>
    </div>

    <div class="header-box">
      <?= $this->Html->link(
        'Cerrar sesión',
        ['plugin'=>'Users','controller'=>'Users','action'=>'logout'],
        ['class'=>'btn-logout']
      ) ?>
    </div>

  </div>
</div>

<!-- CONTENIDO -->
<div class="dashboard-page">

  <!-- Summary -->
  <div style="display:flex;gap:20px;margin-bottom:30px;flex-wrap:wrap;">
    <div class="summary-card summary-green">
      <small>Plan de Seguro</small>
      <h3><?= h($associate->insurance_plan->name ?? 'Sin Plan') ?></h3>
    </div>

    <div class="summary-card summary-yellow">
      <small>Cuota Quincenal</small>
      <h3>$<?= number_format((float)($associate->insurance_plan->biweekly_fee ?? 0),2) ?></h3>
    </div>

    <div class="summary-card summary-green">
      <small>Miembro desde</small>
      <h3><?= $associate->registered_at? $associate->registered_at->format('d/m/Y'):'N/A' ?></h3>
    </div>
  </div>

  <!-- Content -->
  <div style="display:flex;gap:30px;flex-wrap:wrap;">

    <!-- Main -->
    <div style="flex:2;min-width:300px;">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <div>
            <h3>Estado del Seguro</h3>
            <p style="color:var(--gris)">Detalles de tu cuenta hospitalaria</p>
          </div>
          <span class="badge-status"><?= ucfirst(h($associate->member_status ?? 'Activo')) ?></span>
        </div>

        <hr>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:15px;">
          <div><small>Cédula</small><p><strong><?= h($associate->id_card ?? 'N/A') ?></strong></p></div>
          <div><small>Titular</small><p><strong><?= h(($associate->first_name ?? '').' '.($associate->last_name ?? '')) ?></strong></p></div>
          <div><small>Correo</small><p><strong><?= h($associate->user->email ?? 'N/A') ?></strong></p></div>
          <div><small>Teléfono</small><p><strong><?= h($associate->phone ?? 'N/A') ?></strong></p></div>
        </div>

        <div style="margin:18px 0;">
          <small>Uso del servicio</small>
          <div class="progress-bar"><span></span></div>
          <small>Perfil completado al 100%</small>
        </div>

        <p>📅 <strong>Inscrito:</strong> <?= $associate->registered_at? $associate->registered_at->format('d \d\e F \d\e Y'):'N/A' ?></p>
        <p>📍 <strong>Dirección:</strong> <?= h($associate->address ?? 'No registrada') ?></p>

        <div class="benefits">
          <strong>Beneficios incluidos</strong>
          <ul>
            <li>Cobertura por muerte accidental</li>
            <li>Enfermedades graves</li>
            <li>Asistencia médica 24/7</li>
            <li>Protección familiar</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Side -->
    <div style="flex:1;min-width:260px;display:flex;flex-direction:column;gap:20px;">
      <div class="card">
        <h4>Próximo Pago</h4>
        <?php $monthlyFee = ($associate->insurance_plan->biweekly_fee ?? 0) * 2; ?>
        <p style="font-size:1.4rem;font-weight:700;color:var(--verde-oscuro);">
          $<?= number_format((float)$monthlyFee,2) ?>
        </p>
        <?= $this->Html->link(
          'Pagar ahora',
          ['plugin'=>'Payments','controller'=>'Payments','action'=>'pay'],
          ['class'=>'btn-pay']
        ) ?>
      </div>

      <div class="card">
        <h4>Notificaciones</h4>
        <p style="color:var(--gris)">No hay nuevas notificaciones</p>
      </div>
    </div>

  </div>
</div>
