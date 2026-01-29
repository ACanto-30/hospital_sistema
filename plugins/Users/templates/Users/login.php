<?php
// login.php — SOLO INTERFAZ (sin backend)
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Hospital Privado X | Iniciar Sesión</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?= $this->Html->css('users') ?>
</head>

<body>
<div class="wrapper">

    <!-- LOGO -->
    <div class="logo">
        <svg viewBox="0 0 64 64" width="34" height="34">
            <rect x="20" y="4" width="24" height="56" rx="6" fill="#fff"/>
            <rect x="4" y="20" width="56" height="24" rx="6" fill="#fff"/>
            <path d="M18 34h6l4-8 6 16 4-8h8" fill="none" stroke="#0b8f55" stroke-width="3"/>
            <path d="M32 44c-8-6-12-10-12-15a7 7 0 0 1 12-5a7 7 0 0 1 12 5c0 5-4 9-12 15z" fill="#0b8f55"/>
        </svg>
        Hospital Privado X
    </div>

    <!-- CARD -->
    <div class="card">
        <h1>Bienvenido</h1>
        <p>Acceso seguro al sistema médico</p>

        <form>

            <!-- USUARIO -->
            <div class="input-group">
                <span class="icon left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="7" r="4"/>
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                    </svg>
                </span>
                <input type="text" placeholder="Usuario">
            </div>

            <!-- CONTRASEÑA -->
            <div class="input-group">
                <span class="icon left">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="10" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>

                <input type="password" placeholder="Contraseña">

                <span class="icon right">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </span>
            </div>

            <button>Iniciar Sesión</button>
        </form>

        <div class="forgot">
            <a href="#">¿Olvidó su contraseña?</a>
        </div>

        <div class="register">
            ¿No tiene una cuenta?
            <a href="register.php">Regístrese</a>
        </div>
    </div>
</div>
</body>
</html>