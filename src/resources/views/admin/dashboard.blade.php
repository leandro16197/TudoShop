@extends('admin.layouts.base')

@section('header')
<h2>Bienvenido al Panel</h2>
@endsection

@section('content')

<div class="dashboard-cards">

    <div class="card metric">
        <div class="metric-title">Ventas Hoy</div>
        <div class="metric-value" id="ventasHoy">$0</div>
    </div>

    <div class="card metric">
        <div class="metric-title">Pedidos Hoy</div>
        <div class="metric-value" id="pedidosHoy">0</div>
    </div>

    <div class="card metric">
        <div class="metric-title">Clientes Nuevos</div>
        <div class="metric-value" id="clientesNuevos">0</div>
    </div>

    <div class="card metric">
        <div class="metric-title">Pedidos Pendientes</div>
        <div class="metric-value" id="pedidosPendientes">0</div>
    </div>
    <div class="card" style="margin-top:30px;">
    <div class="metric-title">Ventas últimos 7 días</div>
    <canvas id="ventasChart"></canvas>
</div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function () {
        fetch('frontend/v2/panel/metricas')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ventasHoy').innerText = '$' + data.ventas_hoy
                document.getElementById('pedidosHoy').innerText = data.pedidos_hoy
                document.getElementById('clientesNuevos').innerText = data.clientes_hoy
                document.getElementById('pedidosPendientes').innerText = data.pedidos_pendientes

            })
            .catch(error => {
                console.error('Error cargando métricas:', error)
            })
    })
    document.addEventListener('DOMContentLoaded', function () {

        fetch('/frontend/v2/panel/ventas-semana')
            .then(res => res.json())
            .then(data => {

                const labels = data.map(item => item.fecha)
                const ventas = data.map(item => item.total)

                const ctx = document.getElementById('ventasChart').getContext('2d')

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ventas',
                            data: ventas,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34,197,94,0.15)',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#22c55e',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio:false,
                        plugins:{
                            legend:{
                                display:false
                            }
                        },
                        scales:{
                            x:{
                                ticks:{
                                    color:'#94a3b8'
                                },
                                grid:{
                                    color:'rgba(255,255,255,0.05)'
                                }
                            },
                            y:{
                                ticks:{
                                    color:'#94a3b8'
                                },
                                grid:{
                                    color:'rgba(255,255,255,0.05)'
                                }
                            }
                        }
                    }
                });

            })

    })
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection