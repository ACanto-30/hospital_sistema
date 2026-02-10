<?php
$this->assign('title', 'Dashboard - Cajero');

$nombreRol = 'Cajero';
$nombreUsuario = 'Usuario';

if (!empty($user)) {
    $nombreRol = (!empty($user->role) && !empty($user->role->nombre_rol))
        ? $user->role->nombre_rol
        : 'Cajero';

    $nombreUsuario = $user->nombre_completo ?? $user->nombre_usuario ?? 'Usuario';
}

$statusFilter = $statusFilter ?? 'pending';
?>

<div class="dashboard-page dashboard-cashier">

    <div class="dashboard-header">
        <div>
            <h2 class="dashboard-title">Dashboard de <?= h($nombreRol) ?></h2>
            <p class="dashboard-subtitle">Bienvenido, <?= h($nombreUsuario) ?></p>
        </div>

        <div class="dashboard-meta">
            <!--<?= $this->Html->link(
                'Registrar pago',
                ['plugin' => 'Payments', 'controller' => 'Payments', 'action' => 'pay'],
                ['class' => 'btn btn-primary']
            ) ?>-->

            <?= $this->Html->link(
                'Cerrar sesión',
                ['plugin' => 'Users', 'controller' => 'Users', 'action' => 'logout'],
                ['class' => 'btn btn-primary btn-logout']
            ) ?>
        </div>
    </div>

    <hr class="divider">

    <!-- Summary Grid (Estilo Admin) -->
    <div class="summary-grid">
        <div class="summary-card <?= $statusFilter === 'pending' ? 'active-tab' : '' ?>" onclick="window.location='?status=pending'" style="cursor: pointer;">
            <div class="card-content">
                <div class="card-info">
                    <strong class="section-title">Pendientes</strong>
                    <p class="muted">Por aprobar (<?= (int) $resumen['pendientes_totales'] ?>)</p>
                </div>
                <div class="card-icon">
                    <span>⏳</span>
                </div>
            </div>
        </div>

        <div class="summary-card <?= $statusFilter === 'processed' ? 'active-tab' : '' ?>" onclick="window.location='?status=processed'" style="cursor: pointer;">
            <div class="card-content">
                <div class="card-info">
                    <strong class="section-title">Procesados</strong>
                    <p class="muted">Hoy: <?= (int) $resumen['pagos_procesados_hoy'] ?></p>
                </div>
                <div class="card-icon">
                    <span>✅</span>
                </div>
            </div>
        </div>

        <div class="summary-card stats-card" onclick="window.location='?status=all'" style="cursor: pointer;">
            <div class="card-content">
                <div class="card-info">
                    <strong class="section-title">Cobrado Hoy</strong>
                    <p class="muted">B/. <?= number_format((float) $resumen['cobrado_hoy'], 2) ?></p>
                </div>
                <div class="card-icon">
                    <span>💰</span>
                </div>
            </div>
        </div>

        <div class="summary-card stats-card">
            <div class="card-content">
                <div class="card-info">
                    <strong class="section-title">Registros</strong>
                    <p class="muted"><?= $this->Paginator->counter('Total: {{count}}') ?></p>
                </div>
                <div class="card-icon">
                    <span>📊</span>
                </div>
            </div>
        </div>
    </div>

    <div class="summary-card">
        <div class="card-header">
            <h3 class="section-title">
                <?php
                if ($statusFilter === 'pending')
                    echo "Pagos Pendientes de Aprobación";
                elseif ($statusFilter === 'processed')
                    echo "Pagos Procesados Hoy";
                else
                    echo "Listado General de Pagos";
                ?>
            </h3>
            <span class="muted"><?= $this->Paginator->counter('Página {{page}} de {{pages}}') ?></span>
        </div>

        <hr class="divider">

        <div class="table-responsive">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Miembro</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pagos as $p): ?>
                        <tr>
                            <td>#<?= h($p->id) ?></td>
                            <td><?= h(($p->associate->first_name ?? '-') . ' ' . ($p->associate->last_name ?? '')) ?></td>
                            <td>Pago de Cuota</td>
                            <td><strong>B/. <?= number_format((float) $p->amount, 2) ?></strong></td>
                            <td><?= h($p->payment_method->name ?? 'N/A') ?></td>
                            <td>
                                <?php if ((int) $p->payment_status_id === 2): ?>
                                    <span class="dash-badge dash-badge--ok">Aprobado</span>
                                <?php elseif ((int) $p->payment_status_id === 1): ?>
                                    <span class="dash-badge dash-badge--off">Pendiente</span>
                                <?php else: ?>
                                    <span class="dash-badge" style="background:#fee2e2; color:#b91c1c;">Rechazado</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $p->payment_date ? $p->payment_date->format('d/m/Y H:i') : 'N/A' ?></td>
                            <td class="actions">
                                <button type="button" class="action-icon" onclick="toggleEdit('edit-row-<?= $p->id ?>')" title="Procesar Pago">
                                    📝
                                </button>
                                <?php if ($p->proof_image): ?>
                                    <button type="button" class="action-icon" onclick="viewImage('image-modal-<?= $p->id ?>')" title="Ver Comprobante">
                                        👁️
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Row expandible para procesamiento -->
                        <tr id="edit-row-<?= $p->id ?>" class="edit-row" style="display: none;">
                            <td colspan="8">
                                <div class="edit-container payment-edit">
                                    <div class="payment-grid">
                                        <!-- Sección Comprobante -->
                                        <div class="payment-image-section">
                                            <label class="muted small mb-2 d-block">Comprobante de Pago</label>
                                            <?php if ($p->proof_image):
                                                $secureUrl = $this->Url->build([
                                                    '_name' => 'serve_receipt',
                                                    'id' => $p->id
                                                ]);
                                                ?>
                                                <div class="receipt-preview-container" onclick="openLightbox('<?= $secureUrl ?>')">
                                                    <img src="<?= $secureUrl ?>" alt="Comprobante Seguro" class="receipt-img-large">
                                                    <div class="overlay"><span>Haga clic para ampliar (Acceso Protegido)</span></div>
                                                </div>
                                            <?php else: ?>
                                                <div class="no-image">No hay comprobante disponible</div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Sección Edición -->
                                        <div class="payment-fields-section">
                                            <?= $this->Form->create(null, ['url' => ['_name' => 'process_payment', 'id' => $p->id]]) ?>

                                            <div class="form-group mb-4">
                                                <label class="muted small">Monto Recibido (Corregir si es necesario)</label>
                                                <div class="input-with-symbol">
                                                    <span class="symbol">B/.</span>
                                                    <?= $this->Form->control('amount', [
                                                        'label' => false,
                                                        'value' => $p->amount,
                                                        'class' => 'form-input',
                                                        'type' => 'number',
                                                        'step' => '0.01'
                                                    ]) ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-4">
                                                <label class="muted small">Estado del Pago</label>
                                                <?= $this->Form->control('payment_status_id', [
                                                    'label' => false,
                                                    'type' => 'select',
                                                    'options' => $paymentStatuses,
                                                    'value' => $p->payment_status_id,
                                                    'class' => 'form-select',
                                                    'required' => true,
                                                    'empty' => false
                                                ]) ?>
                                            </div>

                                            <div class="edit-actions">
                                                <?= $this->Form->button('Guardar Cambios', ['class' => 'btn-save']) ?>
                                                <button type="button" class="btn-cancel" onclick="toggleEdit('edit-row-<?= $p->id ?>')">Cancelar</button>
                                            </div>
                                            <?= $this->Form->end() ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (count($pagos) === 0): ?>
                        <tr>
                            <td colspan="8" style="text-align:center; padding: 40px;">No hay pagos en esta categoría.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <ul class="pagination">
            <?= $this->Paginator->first('<< primero') ?>
            <?= $this->Paginator->prev('< anterior') ?>
            <?= $this->Paginator->numbers(['modulus' => 5]) ?>
            <?= $this->Paginator->next('siguiente >') ?>
            <?= $this->Paginator->last('último >>') ?>
        </ul>
    </div>
</div>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="Ampliado">
</div>

<style>
    /* Reutilizando y Mejorando Estilos del Dashboard Admin */
    .dashboard-page {
        padding: 20px 0;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white !important;
        border-radius: 12px !important;
        padding: 20px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e5e7eb !important;
        transition: all 0.3s ease !important;
        transform: scale(1) !important;
        opacity: 0.9 !important;
        margin-top: 0 !important;
    }

    .active-tab {
        border-color: #0b8f55 !important;
        box-shadow: 0 10px 15px -3px rgba(11, 143, 85, 0.2) !important;
        transform: scale(1.08) !important;
        opacity: 1 !important;
        z-index: 5 !important;
    }

    .active-tab .section-title {
        color: #0b8f55;
    }

    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-icon {
        font-size: 2rem;
        background: #f0fdf4;
        padding: 10px;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-card {
        background: #f9fafb;
        border-style: dashed;
    }

    .dash-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 10px;
    }

    .dash-table th {
        background: #f8fafc;
        padding: 12px 15px;
        text-align: left;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
    }

    .dash-table td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .action-icon {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        width: 34px;
        height: 34px;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 5px;
    }

    .action-icon:hover {
        background: #e2e8f0;
        transform: scale(1.1);
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        align-items: start;
    }

    .receipt-preview-container {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        cursor: zoom-in;
        max-height: 400px;
    }

    .receipt-img-large {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s;
    }

    .receipt-preview-container:hover .receipt-img-large {
        transform: scale(1.02);
    }

    .overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        padding: 10px;
        text-align: center;
        font-size: 0.8rem;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .receipt-preview-container:hover .overlay {
        opacity: 1;
    }

    .input-with-symbol {
        display: flex;
        align-items: center;
        position: relative;
    }

    .input-with-symbol .symbol {
        position: absolute;
        left: 12px;
        color: #64748b;
        font-weight: 600;
    }

    .input-with-symbol input {
        padding-left: 45px !important;
        font-size: 1.1rem;
        font-weight: 600;
        color: #0b8f55;
    }

    .edit-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-save {
        background: #0b8f55;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        flex: 1;
        transition: background 0.2s;
    }

    .btn-save:hover {
        background: #097d4a;
    }

    .btn-cancel {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        flex: 1;
    }

    /* Lightbox */
    .lightbox {
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        align-items: center;
        justify-content: center;
        padding: 40px;
    }

    .lightbox img {
        max-width: 90%;
        max-height: 90%;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        border-radius: 4px;
    }

    .d-block {
        display: block;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }
</style>

<script>
    function toggleEdit(id) {
        let row = document.getElementById(id);
        let isVisible = row.style.display !== 'none';

        // Cerrar todos primero para limpieza
        document.querySelectorAll('.edit-row').forEach(r => r.style.display = 'none');

        if (!isVisible) {
            row.style.display = 'table-row';
        }
    }

    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').style.display = 'flex';
    }

    function closeLightbox() {
        document.getElementById('lightbox').style.display = 'none';
    }

    function viewImage(id) {
        // Podríamos redirigir o abrir el edit-row automáticamente
        const part = id.split('-').pop();
        toggleEdit('edit-row-' + part);
    }
</script>