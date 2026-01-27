<?php
// login.php — SOLO INTERFAZ (sin backend)
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Hospital Privado X | Iniciar Sesión</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    box-sizing:border-box;
    font-family:"Segoe UI", Arial, sans-serif;
}
body{
    margin:0;
    min-height:100vh;
    background:
        linear-gradient(160deg,#0c7b47 0%, #1fa463 55%, #f2c200 55%, #f2c200 60%, #ffffff 60%);
    display:flex;
    justify-content:center;
    align-items:center;
}
.wrapper{
    width:100%;
    max-width:430px;
    padding:20px;
}

/* LOGO */
.logo{
    display:flex;
    align-items:center;
    justify-content:center;
    color:#fff;
    font-size:24px;
    font-weight:600;
    margin-bottom:25px;
}
.logo svg{
    margin-right:12px;
}

/* CARD */
.card{
    background:#ffffff;
    border-radius:22px;
    padding:30px 26px;
    box-shadow:0 22px 45px rgba(0,0,0,.18);
}
.card h1{
    text-align:center;
    margin:0;
    color:#0c7b47;
    font-size:26px;
}
.card p{
    text-align:center;
    color:#6f6f6f;
    margin:8px 0 24px;
    font-size:14px;
}

/* INPUTS */
.input-group{
    position:relative;
    margin-bottom:14px;
}
.input-group input{
    width:100%;
    padding:14px 44px;
    border-radius:14px;
    border:1px solid #e1e1e1;
    font-size:14px;
    outline:none;
}
.input-group input::placeholder{
    color:#9a9a9a;
}
.input-group .icon{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    width:18px;
    height:18px;
    color:#0c7b47;
}
.input-group .left{
    left:14px;
}
.input-group .right{
    right:14px;
    cursor:pointer;
}

/* BUTTON */
button{
    width:100%;
    margin-top:12px;
    padding:16px;
    border:none;
    border-radius:16px;
    background:linear-gradient(180deg,#1fa463,#0c7b47);
    color:#fff;
    font-size:18px;
    font-weight:600;
    cursor:pointer;
    box-shadow:0 8px 18px rgba(0,0,0,.18);
}

/* TEXTS */
.forgot{
    text-align:center;
    font-size:13px;
    color:#6f6f6f;
    margin-top:16px;
}
.forgot a{
    color:#f2b705;
    font-weight:600;
    text-decoration:none;
}
.register{
    text-align:center;
    margin-top:24px;
    color:#6f6f6f;
}
.register a{
    color:#f2b705;
    font-weight:600;
    text-decoration:none;
}
</style>
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
