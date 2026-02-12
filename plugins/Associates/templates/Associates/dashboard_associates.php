<?php
/**
 * User Dashboard – Life Insurance Mejorado con Mejoras Visuales Avanzadas
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
  --radius:20px;
  --transition:.3s;
  --font-main: 'Segoe UI', Roboto, sans-serif;
}

/* Fondo general con degradado moderno */
.dashboard{
  margin:0;
  min-height:100vh;
  background:linear-gradient(135deg,#0f6b3f 0%,#1b8a5a 80%);
  font-family:var(--font-main);
  color:var(--gris-claro);
}

/* HEADER con glassmorphism */
.dashboard-header{
  background:rgba(11,107,58,0.85);
  padding:60px 0 120px;
  border-bottom-left-radius:var(--radius);
  border-bottom-right-radius:var(--radius);
  box-shadow:0 12px 35px var(--sombra);
  backdrop-filter:blur(12px);
  transition:all var(--transition);
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

/* Header boxes glass + hover animado */
.header-box{
  background:rgba(255,255,255,0.12);
  padding:22px 28px;
  border-radius:var(--radius);
  box-shadow:0 10px 25px var(--sombra);
  backdrop-filter:blur(10px);
  transition:transform var(--transition), box-shadow var(--transition);
}
.header-box:hover{
  transform:translateY(-5px) scale(1.02);
  box-shadow:0 15px 35px var(--sombra);
}

/* Textos header */
.header-box h2{
  margin:0;
  color:#ffffffcc;
  font-weight:900;
  font-size:2rem;
}
.header-box p{
  margin:4px 0 0;
  color:#d9efe4;
  font-size:1rem;
}

/* Botón logout animado */
.btn-logout{
  background:linear-gradient(145deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:14px 28px;
  border-radius:16px;
  font-weight:700;
  text-decoration:none;
  box-shadow:0 8px 25px var(--sombra);
  transition:all var(--transition);
}
.btn-logout:hover{
  transform:scale(1.1);
  box-shadow:0 14px 35px var(--sombra);
}

/* Contenedor principal con sombra y vidrio */
.dashboard-page{
  max-width:1200px;
  margin:-100px auto 40px;
  background:rgba(243,248,245,0.95);
  border-radius:var(--radius);
  padding:40px;
  box-shadow:0 20px 60px var(--sombra);
  backdrop-filter:blur(8px);
}

/* Summary cards neumorphic + hover */
.summary-card{
  flex:1;
  min-width:220px;
  padding:26px;
  border-radius:var(--radius);
  box-shadow:6px 6px 16px var(--sombra), -6px -6px 16px #ffffff33;
  transition:transform var(--transition), box-shadow var(--transition);
}
.summary-card:hover{
  transform:translateY(-6px);
  box-shadow:8px 8px 20px var(--sombra), -8px -8px 20px #ffffff33;
}

.summary-green{
  background:linear-gradient(145deg,#e6f6ee,#dff5e8);
  border-left:6px solid var(--verde);
}
.summary-yellow{
  background:linear-gradient(145deg,#fff4cc,#fff7d1);
  border-left:6px solid var(--amarillo);
}

/* Cards generales glass */
.card{
  background:rgba(255,255,255,0.25);
  padding:26px;
  border-radius:var(--radius);
  box-shadow:0 12px 28px var(--sombra);
  backdrop-filter:blur(14px);
  transition:transform var(--transition), box-shadow var(--transition);
}
.card:hover{
  transform:translateY(-4px);
  box-shadow:0 16px 38px var(--sombra);
}

/* Badge animado */
.badge-status{
  background:var(--verde);
  color:#fff;
  padding:8px 20px;
  border-radius:25px;
  font-size:.95rem;
  font-weight:700;
  box-shadow:0 6px 14px var(--sombra);
  transition:all var(--transition);
}
.badge-status:hover{
  transform:scale(1.1);
  box-shadow:0 10px 20px var(--sombra);
}

/* Progreso animado */
.progress-bar{
  background:#e0e0e0;
  border-radius:14px;
  height:16px;
  overflow:hidden;
  margin-top:6px;
}
.progress-bar span{
  display:block;
  height:100%;
  width:100%;
  background:linear-gradient(145deg,var(--verde),var(--verde-oscuro));
  transition:width .7s ease-in-out;
  border-radius:14px;
}

/* Botón pagar moderno */
.btn-pay{
  display:block;
  text-align:center;
  background:linear-gradient(145deg,var(--verde),var(--verde-oscuro));
  color:#fff;
  padding:16px;
  border-radius:16px;
  margin-top:18px;
  text-decoration:none;
  font-weight:700;
  box-shadow:0 10px 25px var(--sombra);
  transition:all var(--transition);
}
.btn-pay:hover{
  transform:scale(1.1);
  box-shadow:0 16px 40px var(--sombra);
}

/* Lista de beneficios con iconos animados */
.benefits li{
  list-style:none;
  margin-bottom:10px;
  font-weight:500;
  color:var(--gris-claro);
  position:relative;
  padding-left:28px;
  transition:color .3s;
}
.benefits li::before{
  content:"✔";
  position:absolute;
  left:0;
  top:0;
  color:var(--verde);
  font-weight:900;
  transform:scale(1);
  transition:transform .3s;
}
.benefits li:hover::before{
  transform:scale(1.3);
}

/* Clase de borde rojo para campos inválidos con animación */
.field-invalid{
  border:2px solid var(--rojo); /* borde rojo visible */
  padding:4px 8px; /* padding original */
  border-radius:6px; /* borde redondeado original */
  background:#ffe8e6; /* leve fondo rosa para resaltar */
  animation:shake .4s; /* animación de vibración */
}

/* Animación shake */
@keyframes shake{
  0% {transform:translateX(0)}
  25% {transform:translateX(-4px)}
  50% {transform:translateX(4px)}
  75% {transform:translateX(-4px)}
  100% {transform:translateX(0)}
}

/* Scroll sidebar smooth */
.sidebar-scroll{
  max-height:420px;
  overflow-y:auto;
  padding-right:6px;
}
.sidebar-scroll::-webkit-scrollbar{
  width:6px;
}
.sidebar-scroll::-webkit-scrollbar-thumb{
  background:var(--verde);
  border-radius:3px;
}
.sidebar-scroll::-webkit-scrollbar-track{
  background:transparent;
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
        <h4>Últimos Pagos</h4>
        <?php if(!empty($associate->payments)): ?>
          <ul style="padding-left:10px;">
            <?php foreach(array_slice($associate->payments, 0, 5) as $payment): ?>
              <li style="margin-bottom:6px; font-weight:500; color:var(--gris-claro);">
                💵 $<?= number_format((float)$payment->amount,2) ?> - <?= $payment->paid_at->format('d/m/Y') ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <p style="color:var(--gris);">No hay pagos registrados</p>
        <?php endif; ?>
      </div>

      <div class="card">
        <h4>Notificaciones</h4>
        <p style="color:var(--gris)">No hay nuevas notificaciones</p>
      </div>

    </div>

  </div>
</div>
