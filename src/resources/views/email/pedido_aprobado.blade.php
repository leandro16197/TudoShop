<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7f9; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f9; padding: 40px 0; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #333333; border-radius: 12px; shadow: 0 4px 10px rgba(0,0,0,0.05); overflow: hidden; border: 1px solid #e1e8ed; }
        .header { background-color: #0d6efd; padding: 40px; text-align: center; }
        .content { padding: 40px; }
        .card-summary { background-color: #f8fafd; border: 1px solid #e1e8ed; border-radius: 8px; padding: 25px; margin: 20px 0; }
        .footer { background-color: #1a1d20; color: #adb5bd; padding: 40px; font-size: 14px; text-align: center; }
        .btn { background-color: #0d6efd; color: #ffffff !important; padding: 15px 30px; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block; }
        .table-products { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-products td { padding: 12px 0; border-bottom: 1px solid #f0f0f0; font-size: 15px; }
        .total-row td { padding: 10px 0; font-size: 14px; color: #666; }
        .grand-total { font-size: 20px !important; color: #198754 !important; font-weight: bold; border-top: 2px solid #0d6efd; padding-top: 15px !important; }
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
                        <h2 style="color: #212529; margin: 0 0 10px 0;">¡Pago confirmado! 🎉</h2>
                        <p style="font-size: 16px; color: #6c757d; margin: 0;">Hola <strong>{{ $pedido->cliente->nombre ?? 'Cliente' }}</strong>, estamos preparando tu envío.</p>
                    </div>

                    <div class="card-summary">
                        <table width="100%">
                            <tr>
                                <td>
                                    <span style="text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; display: block; margin-bottom: 5px;">Orden</span>
                                    <span style="font-size: 18px; font-weight: bold;">#{{ $pedido->id }}</span>
                                </td>
                                <td style="text-align: right;">
                                    <span style="text-transform: uppercase; font-size: 11px; font-weight: 800; color: #a0aec0; display: block; margin-bottom: 5px;">Estado</span>
                                    <span style="background-color: #d1e7dd; color: #0f5132; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold;">PAGADO</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    @php
                        $subtotal = $pedido->productos->sum(fn($p) => $p->price * $p->pivot->cantidad);

                        $cpTienda = config('app.codigo_postal') ?? '7000';
                        $costoEnvio = 0;

                        if ($pedido->envio) {
                            $costoEnvio = ($pedido->envio->cp !== $cpTienda)
                                ? 1000 + ($subtotal * 0.10)
                                : 1000;
                        }
                    @endphp

                    <h4 style="margin: 30px 0 10px 0; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px;">Detalles de la compra</h4>
                    <table class="table-products">
                        @foreach($pedido->productos as $producto)
                        <tr>
                            <td>
                                <strong>{{ $producto->name }}</strong><br>
                                <span style="font-size: 12px; color: #888;">
                                    Cantidad: {{ $producto->pivot->cantidad }}
                                </span>
                            </td>
                            <td style="text-align: right; vertical-align: middle;">
                                ${{ number_format($producto->price * $producto->pivot->cantidad, 2, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach

                        <tr class="total-row">
                            <td style="padding-top: 20px; text-align: right; padding-right: 15px;">
                                Subtotal:
                            </td>
                            <td style="padding-top: 20px; text-align: right;">
                                ${{ number_format($subtotal, 2, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="total-row">
                            <td style="text-align: right; padding-right: 15px;">
                                Costo de envío:
                            </td>
                            <td style="text-align: right;">
                                ${{ number_format($costoEnvio, 2, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="total-row">
                            <td style="text-align: right; padding-right: 15px;" class="grand-total">
                                Total abonado:
                            </td>
                            <td style="text-align: right;" class="grand-total">
                                ${{ number_format($pedido->pago->total ?? ($subtotal + $costoEnvio), 2, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    <div style="text-align: center; margin-top: 40px;">
                        <a href="{{ url('/perfil') }}" class="btn">Rastrear mi pedido</a>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <h3 style="color: #ffffff; margin: 0 0 10px 0;">Shop<span style="color: #0d6efd;">Tudo</span></h3>
                    <p style="margin: 0 0 20px 0; color: #718096;">Tu librería de confianza siempre cerca de ti.</p>
                    <div style="border-top: 1px solid #333; padding-top: 20px;">
                        <p style="margin: 0; font-size: 12px;">Si tienes dudas, contáctanos:</p>
                        <a href="mailto:leandroovejero16197@gmail.com" style="color: #0d6efd; text-decoration: none; font-weight: bold;">leandroovejero16197@gmail.com</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>