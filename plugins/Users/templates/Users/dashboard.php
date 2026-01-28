<?php
/**
 * User Dashboard – Life Insurance
 */
$this->assign('title', 'Dashboard de Usuario');
?>

<div class="dashboard-page">

  <!-- Header -->
  <div class="dashboard-header">
    <div>
      <h2 class="dashboard-title">Dashboard de Usuario</h2>
      <p class="dashboard-subtitle">Bienvenido, Usuario</p>
    </div>

    <div class="dashboard-meta">
      <strong class="dashboard-meta-title">Seguro de Vida</strong><br>
      <span class="dashboard-meta-code">VID-2024-001234</span>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid">

    <div class="summary-card summary-green">
      <small class="muted">Cobertura Total</small>
      <h3 class="summary-value">$500,000</h3>
    </div>

    <div class="summary-card summary-yellow">
      <small class="muted">Prima Mensual</small>
      <h3 class="summary-value">$350</h3>
    </div>

    <div class="summary-card summary-green">
      <small class="muted">Próximo Pago</small>
      <h3 class="summary-value">14/02/2026</h3>
    </div>

  </div>

  <!-- Main Content -->
  <div class="dashboard-content">

    <!-- Insurance Status -->
    <div class="card">

      <div class="card-header">
        <div>
          <h3 class="section-title">Estado del Seguro de Vida</h3>
          <p class="section-subtitle">Detalles de tu seguro</p>
        </div>
        <span class="status-active">Activo</span>
      </div>

      <hr class="divider">

      <div class="info-grid">
        <div>
          <small class="muted">Número de Seguro</small>
          <p><strong>VID-2024-001234</strong></p>
        </div>

        <div>
          <small class="muted">Titular</small>
          <p><strong>Usuario</strong></p>
        </div>

        <div>
          <small class="muted">Cobertura Total</small>
          <p><strong>$500,000</strong></p>
        </div>

        <div>
          <small class="muted">Prima Mensual</small>
          <p><strong>$350</strong></p>
        </div>
      </div>

      <!-- Coverage Progress -->
      <div class="progress-wrap">
        <small class="muted">Cobertura Utilizada</small>

        <div class="progress-bar">
          <div class="progress-fill" style="width:25%"></div>
        </div>

        <small class="muted">Has utilizado el 25% de tu cobertura total</small>
      </div>

      <hr class="divider">

      <p>📅 <strong>Fecha de inicio:</strong> 15 de enero de 2024</p>
      <p>⏳ <strong>Vigencia:</strong> Hasta 15 de enero de 2029</p>

      <!-- Benefits -->
      <div class="benefits-box">
        <strong class="benefits-title">Beneficios incluidos</strong>
        <ul class="benefits-list">
          <li>✔ Cobertura por muerte accidental</li>
          <li>✔ Enfermedades graves</li>
          <li>✔ Asistencia médica 24/7</li>
          <li>✔ Protección familiar</li>
        </ul>
      </div>
    </div>

    <!-- Payments & Notifications -->
    <div class="side-col">

      <!-- Upcoming Payments -->
      <div class="card">
        <h4 class="section-title">Próximos Pagos</h4>

        <div class="pay-box">
          <strong>Prima Mensual</strong>
          <p>$350.00</p>
          <small class="muted">Vence: 14/02/2026</small>

          <button class="btn btn-primary" type="button">Pagar ahora</button>
        </div>

        <button class="btn btn-outline" type="button">Ver calendario de pagos</button>
      </div>

      <!-- Notifications -->
      <div class="card">
        <h4 class="section-title">Notificaciones</h4>

        <div class="notification">
          <strong>Tu pago de febrero está próximo a vencer</strong>
          <p class="muted" style="margin:4px 0 0;">Hace 2 días</p>
        </div>
      </div>

    </div>
  </div>
</div>
