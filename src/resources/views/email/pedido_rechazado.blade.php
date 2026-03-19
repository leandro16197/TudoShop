<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f9; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f9; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #333333; border-radius: 12px; overflow: hidden; border: 1px solid #e1e8ed; }
        .header { background-color: #dc3545; padding: 40px; text-align: center; }
        .content { padding: 40px; }
        .card-alert { background-color: #fff8f8; border: 1px solid #f5c6cb; border-radius: 8px; padding: 25px; margin: 20px 0; }
        .footer { background-color: #1a1d20; color: #adb5bd; padding: 40px; font-size: 14px; text-align: center; }
        .btn-retry { background-color: #212529; color: #ffffff !important; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; }
        .info-list { margin: 20px 0; padding: 0; list-style: none; font-size: 14px; color: #666; }
        .info-list li { margin-bottom: 10px; padding-left: 20px; position: relative; }
        .info-list li::before { content: '•'; position: absolute; left: 0; color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1 style="margin: 0; font-size: 32px; color: #ffffff; letter-spacing: -1px;">Shop<span style="font-weight: 300;">Tudo</span></h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h2 style="color: #dc3545; margin: 0 0 10px 0;">Problema con el pago ❌</h2>
                        <p style="font-size: 16px; color: #6c757d; margin: 0;">Hola <strong>{{ $pedido->cliente->nombre ?? 'Cliente' }}</strong>, tu pedido ha sido registrado, pero el pago no pudo completarse.</p>
                    </div>

                    <div class="card-alert">
                        <table width="100%">
                            <tr>
                                <td>
                                    <span style="text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; display: block; margin-bottom: 5px;">Orden registrada</span>
                                    <span style="font-size: 18px; font-weight: bold;">#{{ $pedido->id }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <span style="text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; display: block; margin-bottom: 5px;">Estado actual</span>
                                    <span style="background-color: #fff3cd; color: #856404; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">PENDIENTE DE PAGO</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="color: #444; line-height: 1.6;">
                        <p>No te preocupes, <strong>tu pedido no se ha perdido</strong>. Hemos guardado los detalles de tu compra para que no tengas que elegir los productos nuevamente.</p>
                        
                        <p>Puedes completar el pago de forma segura haciendo clic en el siguiente botón:</p>
                    </div>

                    <div style="text-align: center; margin-top: 40px;">
                        <a href="{{ url('/checkout/' . $pedido->id) }}" class="btn-retry">Ir a pagar mi pedido</a>
                    </div>

                    <p style="text-align: center; font-size: 13px; color: #888; margin-top: 25px;">
                        Si prefieres ver todos tus pedidos, puedes ingresar a tu <a href="{{ url('/perfil') }}" style="color: #0d6efd;">perfil de usuario</a>.
                    </p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <h3 style="color: #ffffff; margin: 0 0 10px 0;">Shop<span style="color: #dc3545;">Tudo</span></h3>
                    <p style="margin: 0 0 20px 0; color: #718096;">¿Necesitas ayuda? Responde a este correo.</p>
                    <div style="border-top: 1px solid #333; padding-top: 20px;">
                        <a href="mailto:leandroovejero16197@gmail.com" style="color: #adb5bd; text-decoration: none; font-weight: bold;">leandroovejero16197@gmail.com</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>