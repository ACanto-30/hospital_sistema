<div style="
    max-width: 420px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
    background: #f5f7fa;
    min-height: 100vh;
">

    <!-- Header -->
    <div style="
        background: linear-gradient(135deg, #6b7c93, #4e637d);
        color: white;
        padding: 20px;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    ">
        <div style="display:flex; align-items:center;">
            <span style="font-size:20px; margin-right:10px;">←</span>
            <h2 style="margin:0; font-size:18px;">Pagar</h2>
        </div>

        <div style="margin-top:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <strong>Métodos de pago</strong>
                <span style="font-size:14px;">Ver todos →</span>
            </div>

            <div style="
                display:flex;
                gap:10px;
                margin-top:15px;
            ">
                <div style="background:white; padding:10px; border-radius:10px;">
                    <strong>VISA</strong>
                </div>
                <div style="background:white; padding:10px; border-radius:10px;">
                    MasterCard
                </div>
                <div style="background:white; padding:10px; border-radius:10px;">
                    AmEx
                </div>
                <div style="background:white; padding:10px; border-radius:10px;">
                    PayPal
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div style="padding:20px;">

        <h3 style="font-size:16px; margin-bottom:15px;">
            Tus datos de pago
        </h3>

        <!-- Titular -->
        <label style="font-size:14px;">Titular de la tarjeta</label>
        <input type="text" style="
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
        ">

        <!-- Número -->
        <label style="font-size:14px;">Número de la tarjeta</label>
        <input type="text" placeholder="XXXX XXXX XXXX XXXX" style="
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
            border-radius:8px;
            border:1px solid #ccc;
        ">

        <!-- Fecha y CVV -->
        <div style="display:flex; gap:10px;">
            <div style="flex:1;">
                <label style="font-size:14px;">Fecha de vencimiento</label>
                <input type="text" placeholder="MM/YYYY" style="
                    width:100%;
                    padding:10px;
                    margin-top:5px;
                    border-radius:8px;
                    border:1px solid #ccc;
                ">
            </div>

            <div style="flex:1;">
                <label style="font-size:14px;">
                    CVV <span title="Código de seguridad">ⓘ</span>
                </label>
                <input type="text" placeholder="Ej. 123" style="
                    width:100%;
                    padding:10px;
                    margin-top:5px;
                    border-radius:8px;
                    border:1px solid #ccc;
                ">
            </div>
        </div>

        <!-- Monto -->
        <div style="
            margin-top:20px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        ">
            <strong>Monto total</strong>
            <span style="font-size:14px;">Ver detalles →</span>
        </div>

        <div style="margin-top:10px;">
            <strong style="font-size:18px;">$ USD</strong>
        </div>

        <!-- Checkbox -->
        <div style="margin-top:15px;">
            <label style="font-size:14px;">
                <input type="checkbox"> Guardar datos para futuras compras
            </label>
        </div>

        <!-- Botón -->
        <button style="
            width:100%;
            margin-top:20px;
            padding:14px;
            background:#4e637d;
            color:white;
            border:none;
            border-radius:10px;
            font-size:16px;
            cursor:pointer;
        ">
            🔒 Pagar ahora
        </button>

    </div>
</div>
