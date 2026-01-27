<?php
/**
 * Dashboard de Usuario – Seguro de Vida
 */
?>

<div style="padding: 24px; font-family: Arial, sans-serif; background:#f6f7f9;">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h2 style="margin:0;">Dashboard de Usuario</h2>
            <p style="color:#6b7280; margin-top:4px;">Bienvenido, Usuario</p>
        </div>
        <div style="text-align:right;">
            <strong>Seguro de Vida</strong><br>
            <span style="color:#6b7280;">VID-2024-001234</span>
        </div>
    </div>

    <!-- Cards resumen -->
    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px; margin-bottom:20px;">

        <div style="background:#fff; padding:16px; border-radius:12px;">
            <strong>Cobertura Total</strong>
            <h3>$500,000</h3>
        </div>

        <div style="background:#fff; padding:16px; border-radius:12px;">
            <strong>Prima Mensual</strong>
            <h3>$350</h3>
        </div>

        <div style="background:#fff; padding:16px; border-radius:12px;">
            <strong>Próximo Pago</strong>
            <h3>14/2/2026</h3>
        </div>

    </div>

    <!-- Contenido principal -->
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:16px;">

        <!-- Estado del Seguro -->
        <div style="background:#fff; padding:20px; border-radius:12px;">
            <div style="display:flex; justify-content:space-between;">
                <div>
                    <h3 style="margin:0;">Estado del Seguro de Vida</h3>
                    <p style="color:#6b7280;">Detalles de tu seguro</p>
                </div>
                <span style="background:#22c55e; color:#fff; padding:4px 12px; border-radius:20px;">
                    Activo
                </span>
            </div>

            <hr style="margin:16px 0;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <small>Número de Seguro</small>
                    <p><strong>VID-2024-001234</strong></p>
                </div>
                <div>
                    <small>Titular</small>
                    <p><strong>Usuario</strong></p>
                </div>
                <div>
                    <small>Cobertura Total</small>
                    <p><strong>$500,000</strong></p>
                </div>
                <div>
                    <small>Prima Mensual</small>
                    <p><strong>$350</strong></p>
                </div>
            </div>

            <!-- Progreso -->
            <div style="margin-top:20px;">
                <small>Cobertura Utilizada</small>
                <div style="background:#e5e7eb; border-radius:8px; height:8px;">
                    <div style="width:25%; background:#111827; height:8px; border-radius:8px;"></div>
                </div>
                <small>Has utilizado el 25% de tu cobertura total</small>
            </div>

            <hr style="margin:16px 0;">

            <p>📅 <strong>Fecha de inicio:</strong> 15 de Enero, 2024</p>
            <p>⏳ <strong>Vigencia:</strong> Hasta 15 de Enero, 2029</p>

            <!-- Beneficios -->
            <div style="background:#eff6ff; padding:16px; border-radius:12px; margin-top:16px;">
                <strong>Beneficios Incluidos</strong>
                <ul>
                    <li>✔ Cobertura de muerte accidental</li>
                    <li>✔ Enfermedades graves</li>
                    <li>✔ Asistencia médica 24/7</li>
                    <li>✔ Protección familiar</li>
                </ul>
            </div>
        </div>

        <!-- Pagos y notificaciones -->
        <div style="display:flex; flex-direction:column; gap:16px;">

            <!-- Próximos pagos -->
            <div style="background:#fff; padding:16px; border-radius:12px;">
                <h4>Próximos Pagos</h4>

                <div style="border:1px solid #e5e7eb; padding:12px; border-radius:8px; margin-bottom:12px;">
                    <strong>Prima Mensual</strong>
                    <p>$350.00</p>
                    <small>Vence: 14/2/2026</small><br>
                    <button style="margin-top:8px; width:100%; background:#111827; color:#fff; padding:8px; border:none; border-radius:8px;">
                        Pagar Ahora
                    </button>
                </div>

                <button style="width:100%; background:#fff; border:1px solid #e5e7eb; padding:8px; border-radius:8px;">
                    Ver Calendario de Pagos
                </button>
            </div>

            <!-- Notificaciones -->
            <div style="background:#fff; padding:16px; border-radius:12px;">
                <h4>Notificaciones</h4>
                <div style="background:#eff6ff; padding:12px; border-radius:8px;">
                    <strong>Tu pago de febrero está próximo a vencer</strong>
                    <p style="color:#6b7280;">Hace 2 días</p>
                </div>
            </div>

        </div>
    </div>
</div>
