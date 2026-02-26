<!doctype html>
<html lang="es">

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($this->fetch('title') ?: 'Hospital Privado X') ?></title>

    <?= $this->Html->css(['app', 'user-dashboard']) ?>
    <?= $this->fetch('css') ?>

    <style>
        /* ===== Modal Flash (emergente) ===== */
        .flash-overlay{
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 18px;
        }
        .flash-overlay[hidden]{ display:none; }

        .flash-modal{
            width: min(560px, 96vw);
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 18px 50px rgba(0,0,0,.25);
            overflow: hidden;
            animation: popIn .18s ease-out;
        }
        @keyframes popIn{
            from { transform: translateY(6px) scale(.98); opacity:.7; }
            to { transform: translateY(0) scale(1); opacity:1; }
        }

        .flash-modal-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            padding: 12px 14px;
            background: #0b6b3a;
            color: #fff;
        }
        .flash-close{
            border:none;
            background: transparent;
            color:#fff;
            font-size: 18px;
            cursor:pointer;
            padding: 2px 6px;
            line-height: 1;
            opacity:.9;
        }
        .flash-close:hover{ opacity:1; }

        .flash-modal-body{
            padding: 14px 16px;
            color:#111827;
            font-size: 15px;
        }

        .flash-modal-body .message{
            margin: 0;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #bbf7d0;
            background: #ecfdf5;
            color: #065f46;
        }
        .flash-modal-body .message.error{
            border-color:#fecaca;
            background:#fef2f2;
            color:#991b1b;
        }

        .flash-modal-footer{
            padding: 12px 14px;
            display:flex;
            justify-content:flex-end;
            gap:8px;
            background:#f9fafb;
            border-top:1px solid #e5e7eb;
        }
        .flash-ok{
            background:#0b6b3a;
            color:#fff;
            border:none;
            padding: 9px 14px;
            border-radius:10px;
            cursor:pointer;
            font-weight:600;
        }
        .flash-ok:hover{ filter: brightness(1.05); }

        .dash-card > .message,
        .dash-card > .flash,
        .dash-card > .flash-message{
            display:none !important;
        }
    </style>
</head>

<body>
    <div class="dashboard">

        <header class="dash-topbar">
            <div class="dash-brand">
                <!--<span class="dash-logo">🏥</span>-->
                <!--<span class="dash-brand-text">Hospital Privado X</span>-->
            </div>
        </header>

        <?php
        
            $flashHtml = trim((string)$this->Flash->render());
        ?>

        <?php if (!empty($flashHtml)): ?>
            <div class="flash-overlay" id="flashOverlay" role="dialog" aria-modal="true">
                <div class="flash-modal" role="document">
                    <div class="flash-modal-header">
                        <strong>Notificación</strong>
                        <button type="button" class="flash-close" id="flashCloseBtn" aria-label="Cerrar">✕</button>
                    </div>

                    <div class="flash-modal-body">
                        <?= $flashHtml ?>
                    </div>

                    <div class="flash-modal-footer">
                        <button type="button" class="flash-ok" id="flashOkBtn">Aceptar</button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <main class="dash-container">
            <section class="dash-card">
               
                <?= $this->fetch('content') ?>
            </section>
        </main>

    </div>

    <?= $this->fetch('script') ?>

    <script>
        (function(){
            const overlay = document.getElementById('flashOverlay');
            if (!overlay) return;

            const closeBtn = document.getElementById('flashCloseBtn');
            const okBtn = document.getElementById('flashOkBtn');

            function closeModal(){
                overlay.setAttribute('hidden', 'hidden');
            }

            // Cerrar al tocar el fondo
            overlay.addEventListener('click', function(e){
                if (e.target === overlay) closeModal();
            });

            if (closeBtn) closeBtn.addEventListener('click', closeModal);
            if (okBtn) okBtn.addEventListener('click', closeModal);

            // Cerrar con ESC
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape') closeModal();
            });
        })();
    </script>
</body>

</html>