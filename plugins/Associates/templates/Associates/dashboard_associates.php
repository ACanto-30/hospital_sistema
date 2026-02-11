<?php
/**
 * User Dashboard – Life Insurance Mejorado con Feedback de Campos
 */
$this->assign('title', 'Dashboard de Usuario');

// Función helper para validar campos
function fieldClass($value){
    return empty($value) ? 'field-invalid' : '';
}
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
  --gris-claro:#888;
  --rojo:#e74c3c;
  --sombra:#00000033;
}

/* Fondo general */
.dashboard{
  margin:0;
  min-height:100vh;
  background:linear-gradient(135deg,#0f6b3f 0%,#1b8a5a 70%);
  font-family:system-ui,-apple-system,BlinkMacSystemFont,sans-serif;
  color:var(--gris-claro);
}

/* HEADER VERDE */
.dashboard-header{
  background:linear-gradient(135deg,#0f6b3f,#1b8a5a);
  padding:50px 0 100px;
  border-bottom-left-radius:30px;
  border-bottom-right-radius:30px;
}

/* Header segmentado */
.dashboard-header-inner{
  max-width:1200px;
  margin:0 auto;
  padding:0 32px;
  display:grid;
  grid-template-columns:1fr 1.5fr auto;
  gap:20px;
  align-items:center;
}

/* Bloques del header */
.header-box{
  background:rgba(255,255,255,0.14);
  padding:20px 28px;
  border-radius:20px;
  box-shadow:0 10px 25px var(--sombra);
  backdrop-filter:blur(8px);
  transition:transform .2s;
}

.header-box:hover{
  transform:translateY(-2px);
}

/* Textos header */
.header-box h2{
  margin:0;
  color:#fff;
  font-weight:700;
  font-size:1.8rem;
}

.header-box p{
  margin:4px 0 0;
  color:#d9efe4;
  font-size:1rem;
}

/* Botón logout */
.btn-logout{
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:12px 24px;
  border-radius:14px;
  font-weight:600;
  text-decoration:none;
  box-shadow:0 6px 16px var(--sombra);
  transition:all .3s;
}
.btn-logout:hover{
  transform:scale(1.05);
}

/* Contenedor principal */
.dashboard-page{
  max-width:1200px;
  margin:-80px auto 40px;
  background:var(--verde-blanco);
  border-radius:24px;
  padding:36px;
  box-shadow:0 30px 60px var(--sombra);
}

/* Summary cards */
.summary-card{
  flex:1;
  min-width:220px;
  padding:24px;
  border-radius:16px;
  box-shadow:0 10px 25px var(--sombra);
  transition:transform .2s;
}
.summary-card:hover{
  transform:translateY(-3px);
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
  padding:24px;
  border-radius:16px;
  box-shadow:0 10px 25px var(--sombra);
  backdrop-filter:blur(6px);
  transition:transform .2s;
}
.card:hover{
  transform:translateY(-2px);
}

/* Badge estado */
.badge-status{
  background:var(--verde);
  color:#fff;
  padding:6px 16px;
  border-radius:20px;
  font-size:.9rem;
  font-weight:600;
  box-shadow:0 4px 12px var(--sombra);
}

/* Progreso */
.progress-bar{
  background:#e0e0e0;
  border-radius:12px;
  height:14px;
  overflow:hidden;
  margin-top:6px;
}

.progress-bar span{
  display:block;
  height:100%;
  width:100%;
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
  transition:width .5s ease-in-out;
}

/* Botón pagar */
.btn-pay{
  display:block;
  text-align:center;
  background:linear-gradient(135deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:14px;
  border-radius:12px;
  margin-top:16px;
  text-decoration:none;
  font-weight:700;
  box-shadow:0 8px 20px var(--sombra);
  transition:all .3s;
}
.btn-pay:hover{
  transform:scale(1.05);
}

/* Listas */
.benefits li{
  list-style:none;
  margin-bottom:8px;
  font-weight:500;
  color:var(--gris-claro);
}

.benefits li::before{
  content:"✔";
  color:var(--verde);
  font-weight:700;
  margin-right:8px;
}

/* Clase de borde rojo para campos inválidos */
.field-invalid{
  border:2px solid var(--rojo);
  padding:4px 8px;
  border-radius:6px;
}
</style>
<?php $this->end(); ?>

<!-- HEADER -->
<div class="dashboard-header">
  <div class="dashboard-header-inner">

    <div class="header-box">
      <h2>Panel de Control</h2>
    </div>

    <div class="header-box">
      <p>Bienvenido, <?= h(($associate->first_name ?? 'Usuario').' '.($associate->last_name ?? '')) ?></p>
    </div>

    <div class="header-box">
      <?= $this->Html->link('Cerrar sesión', ['plugin'=>'Users','controller'=>'Users','action'=>'logout'], ['class'=>'btn-logout']) ?>
    </div>

  </div>
</div>

<!-- CONTENIDO -->
<div class="dashboard-page">

  <!-- Summary -->
  <div style="display:flex;gap:20px;margin-bottom:35px;flex-wrap:wrap;">
    <div class="summary-card summary-green">
      <small>Plan de Seguro</small>
      <h3 class="<?= fieldClass($associate->insurance_plan->name ?? null) ?>"><?= h($associate->insurance_plan->name ?? 'Sin Plan') ?></h3>
    </div>

    <div class="summary-card summary-yellow">
      <small>Cuota Quincenal</small>
      <h3 class="<?= fieldClass($associate->insurance_plan->biweekly_fee ?? null) ?>">
        $<?= number_format((float)($associate->insurance_plan->biweekly_fee ?? 0),2) ?>
      </h3>
    </div>

    <div class="summary-card summary-green">
      <small>Miembro desde</small>
      <h3 class="<?= fieldClass($associate->registered_at ?? null) ?>">
        <?= $associate->registered_at? $associate->registered_at->format('d/m/Y'):'N/A' ?>
      </h3>
    </div>
  </div>

  <!-- Content -->
  <div style="display:flex;gap:30px;flex-wrap:wrap;">

    <!-- Main -->
    <div style="flex:2;min-width:320px;">
      <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;">
          <div>
            <h3>Estado del Seguro</h3>
            <p style="color:var(--gris)">Detalles de tu cuenta hospitalaria</p>
          </div>
          <span class="badge-status"><?= ucfirst(h($associate->member_status ?? 'Activo')) ?></span>
        </div>

        <hr>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-top:10px;">
          <div>
            <small>Cédula</small>
            <p class="<?= fieldClass($associate->id_card ?? null) ?>">
              <strong><?= h($associate->id_card ?? 'N/A') ?></strong>
            </p>
          </div>
          <div>
            <small>Titular</small>
            <p class="<?= fieldClass(($associate->first_name ?? '').($associate->last_name ?? '')) ?>">
              <strong><?= h(($associate->first_name ?? '').' '.($associate->last_name ?? '')) ?></strong>
            </p>
          </div>
          <div>
            <small>Correo</small>
            <p class="<?= fieldClass($associate->user->email ?? null) ?>">
              <strong><?= h($associate->user->email ?? 'N/A') ?></strong>
            </p>
          </div>
          <div>
            <small>Teléfono</small>
            <p class="<?= fieldClass($associate->phone ?? null) ?>">
              <strong><?= h($associate->phone ?? 'N/A') ?></strong>
            </p>
          </div>
        </div>

        <div style="margin:20px 0;">
          <small>Uso del servicio</small>
          <div class="progress-bar"><span style="width:100%"></span></div>
          <small>Perfil completado al 100%</small>
        </div>

        <p>📅 <strong>Inscrito:</strong> 
          <span class="<?= fieldClass($associate->registered_at ?? null) ?>">
            <?= $associate->registered_at? $associate->registered_at->format('d \d\e F \d\e Y'):'N/A' ?>
          </span>
        </p>
        <p>📍 <strong>Dirección:</strong> 
          <span class="<?= fieldClass($associate->address ?? null) ?>">
            <?= h($associate->address ?? 'No registrada') ?>
          </span>
        </p>

        <div class="benefits" style="margin-top:18px;">
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
    <div style="flex:1;min-width:280px;display:flex;flex-direction:column;gap:22px;">
      <div class="card">
        <h4>Próximo Pago</h4>
        <?php $monthlyFee = ($associate->insurance_plan->biweekly_fee ?? 0) * 2; ?>
        <p class="<?= fieldClass($monthlyFee) ?>" style="font-size:1.5rem;font-weight:700;color:var(--verde-oscuro);">
          $<?= number_format((float)$monthlyFee,2) ?>
        </p>
        <?= $this->Html->link('Pagar ahora', ['plugin'=>'Payments','controller'=>'Payments','action'=>'pay'], ['class'=>'btn-pay']) ?>
      </div>

      <div class="card">
        <h4>Notificaciones</h4>
        <p style="color:var(--gris)">No hay nuevas notificaciones</p>
      </div>
    </div>

  </div>
</div>
