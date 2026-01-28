<?php
/**
 * User Dashboard – Life Insurance
 */
?>

<div style="padding:24px; font-family:Arial, sans-serif; background:#f3fdf7;">

    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="margin:0; color:#157347;">Dashboard de Usuario</h2>
            <p style="color:#6b7280; margin-top:4px;">Bienvenido, Usuario</p>
        </div>
        <div style="text-align:right;">
            <strong style="color:#157347;">Seguro de Vida</strong><br>
            <span style="color:#6b7280;">VID-2024-001234</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:24px;">

        <div style="background:#ffffff; padding:18px; border-radius:14px; border-left:6px solid #1f9d55;">
            <small style="color:#6b7280;">Cobertura Total</small>
            <h3 style="margin:8px 0 0; color:#157347;">$500,000</h3>
        </div>

        <div style="background:#ffffff; padding:18px; border-radius:14px; border-left:6px solid #facc15;">
            <small style="color:#6b7280;">Prima Mensual</small>
            <h3 style="margin:8px 0 0; color:#157347;">$350</h3>
        </div>

        <div style="background:#ffffff; padding:18px; border-radius:14px; border-left:6px solid #1f9d55;">
            <small style="color:#6b7280;">Próximo Pago</small>
            <h3 style="margin:8px 0 0; color:#157347;">14/02/2026</h3>
        </div>

    </div>

    <!-- Main Content -->
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px;">

        <!-- Insurance Status -->
        <div style="background:#ffffff; padding:24px; border-radius:16px;">

            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <h3 style="margin:0; color:#157347;">Estado del Seguro de Vida</h3>
                    <p style="color:#6b7280; margin-top:4px;">Detalles de tu seguro</p>
                </div>
                <span style="background:#1f9d55; color:#fff; padding:6px 14px; border-radius:20px; font-size:13px;">
                    Activo
                </span>
            </div>

            <hr style="margin:20px 0; border:none; border-top:1px solid #e5e7eb;">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                <div>
                    <small style="color:#6b7280;">Número de Seguro</small>
                    <p><strong>VID-2024-001234</strong></p>
                </div>
                <div>
                    <small style="color:#6b7280;">Titular</small>
                    <p><strong>Usuario</strong></p>
                </div>
                <div>
                    <small style="color:#6b7280;">Cobertura Total</small>
                    <p><strong>$500,000</strong></p>
                </div>
                <div>
                    <small style="color:#6b7280;">Prima Mensual</small>
                    <p><strong>$350</strong></p>
                </div>
            </div>

            <!-- Coverage Progress -->
            <div style="margin-top:24px;">
                <small style="color:#6b7280;">Cobertura Utilizada</small>
                <div style="background:#e6f4ec; border-radius:10px; height:10px; margin-top:6px;">
                    <div style="width:25%; background:#1f9d55; height:10px; border-radius:10px;"></div>
                </div>
                <small style="color:#6b7280;">Has utilizado el 25% de tu cobertura total</small>
            </div>

            <hr style="margin:20px 0; border:none; border-top:1px solid #e5e7eb;">

            <p>📅 <strong>Fecha de inicio:</strong> 15 de enero de 2024</p>
            <p>⏳ <strong>Vigencia:</strong> Hasta 15 de enero de 2029</p>

            <!-- Benefits -->
            <div style="background:#e6f4ec; padding:18px; border-radius:14px; margin-top:20px;">
                <strong style="color:#157347;">Beneficios incluidos</strong>
                <ul style="margin-top:10px;">
                    <li>✔ Cobertura por muerte accidental</li>
                    <li>✔ Enfermedades graves</li>
                    <li>✔ Asistencia médica 24/7</li>
                    <li>✔ Protección familiar</li>
                </ul>
            </div>
        </div>

        <!-- Payments & Notifications -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- Upcoming Payments -->
            <div style="background:#ffffff; padding:20px; border-radius:16px;">
                <h4 style="margin-top:0; color:#157347;">Próximos Pagos</h4>

                <div style="border:1px solid #e5e7eb; padding:14px; border-radius:12px; margin-bottom:14px;">
                    <strong>Prima Mensual</strong>
                    <p>$350.00</p>
                    <small>Vence: 14/02/2026</small>

                    <button style="
                        margin-top:10px;
                        width:100%;
                        background:#157347;
                        color:#fff;
                        padding:10px;
                        border:none;
                        border-radius:10px;
                        cursor:pointer;
                    ">
                        Pagar ahora
                    </button>
                </div>

                <button style="
                    width:100%;
                    background:#ffffff;
                    border:1px solid #1f9d55;
                    color:#157347;
                    padding:10px;
                    border-radius:10px;
                    cursor:pointer;
                ">
                    Ver calendario de pagos
                </button>
            </div>

            <!-- Notifications -->
            <div style="background:#ffffff; padding:20px; border-radius:16px;">
                <h4 style="margin-top:0; color:#157347;">Notificaciones</h4>

                <div style="background:#fff7cc; padding:14px; border-radius:12px;">
                    <strong>Tu pago de febrero está próximo a vencer</strong>
                    <p style="color:#6b7280; margin:4px 0 0;">Hace 2 días</p>
                </div>
            </div>

        </div>
    </div>
</div>
