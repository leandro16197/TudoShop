@extends('admin.layouts.base')

@section('header')
<h2>Bienvenido al Panel</h2>
@endsection
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr); 
        gap: 20px;
        width: 100%;
    }
    .metric {
        min-width: 0; 
        margin-bottom: 0; 
    }

    .chart-card {
        grid-column: 1 / -1; 
        width: 100% !important;
        min-height: 400px;
    }

    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

    @media (max-width: 992px) {
        .dashboard-container {
            grid-template-columns: 1fr;
        }
    }
</style>
@section('content')

<div class="dashboard-container">
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

    <div class="card chart-card">
        <div class="chart-header">
            <h3>Ventas últimos 30 días</h3>
            <span class="chart-sub">Ingresos y pedidos diarios</span>
        </div>
        <div class="chart-container">
            <canvas id="ventasChart"></canvas>
        </div>
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

        fetch('frontend/v2/panel/ventas-mes')
        .then(res => res.json())
        .then(data => {

            const labels = data.map(i => i.fecha)
            const ventas = data.map(i => i.total)
            const pedidos = data.map(i => i.pedidos)

            const ctx = document.getElementById('ventasChart')

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                    {
                        label: 'Ventas $',
                        data: ventas,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.15)',
                        borderWidth: 3,
                        tension: 0.35,
                        pointRadius: 3,
                        fill: true
                    },
                    {
                        label: 'Pedidos',
                        data: pedidos,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.15)',
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 3,
                        fill: false
                    }]
                },

                options: {

                    interaction:{
                        mode:'index',
                        intersect:false
                    },

                    responsive:true,
                    maintainAspectRatio:false,

                    plugins:{
                        legend:{
                            labels:{
                                color:'#cbd5f5'
                            }
                        },
                        tooltip:{
                            backgroundColor:'#020617',
                            borderColor:'#1e293b',
                            borderWidth:1,
                            callbacks:{
                                label:function(context){
                                    let value = context.raw.toLocaleString()
                                    return context.dataset.label + ": $" + value
                                }
                            }
                        }
                    },

                    scales:{
                        x:{
                            ticks:{color:'#94a3b8'},
                            grid:{color:'rgba(255,255,255,0.05)'}
                        },
                        y:{
                            ticks:{
                                color:'#94a3b8',
                                callback:value => "$" + value.toLocaleString()
                            },
                            grid:{color:'rgba(255,255,255,0.05)'}
                        }
                    }
                }
            })

        })

        })
</script>
@endsection