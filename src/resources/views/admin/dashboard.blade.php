@extends('admin.layouts.base')

@section('header')
    <h2>Panel Operativo</h2>
@endsection

<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        width: 100%;
    }

    .column-group {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Tarjetas de Métricas */
    .metric {
        min-width: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 1.25rem;
    }

    .metric-value {
        font-size: 2rem !important;
        line-height: 1;
        margin: 5px 0;
        font-weight: bold;
    }

    /* Ranking */
    .ranking-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .ranking-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 8px 0;
        font-size: 0.9rem;
    }

    .ranking-pos {
        width: 24px;
        height: 24px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        margin-right: 10px;
    }

    /* Gráfico */
    .chart-card {
        grid-column: 1 / -1;
        min-height: 400px;
        padding: 1.5rem;
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

        .card.metric {
            grid-column: span 1 !important;
        }
    }

    .btn-outline-light.border-0:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .btn-outline-light.active {
        background-color: rgba(25, 135, 84, 0.2);
    }
</style>

@section('content')
    <div class="dashboard-container">
        <div class="column-group">
            <div class="card metric border-start border-danger border-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title text-danger fw-bold">Stock Crítico</div>
                        <div class="metric-value text-white" id="stockCritico">0</div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalStockCritico">
                        Ver detalles
                    </button>
                </div>
                <small class="text-white-50">Productos agotándose</small>
            </div>

            <div class="card metric border-start border-primary border-3">
                <div class="metric-title text-primary fw-bold">Pedidos Pendientes</div>
                <div class="metric-value text-white" id="pedidosPendientes">0</div>
                <small class="text-white-50">Pendientes de envío</small>
            </div>
        </div>

        <div class="card metric" style="grid-column: span 2;">
            <div class="metric-title text-info fw-bold mb-3">Top 3 Rotación (Esta Semana)</div>
            <div id="topProductosList">
                <div class="text-white-50 small">Cargando ranking...</div>
            </div>
        </div>

        <div class="column-group">
            <div class="card metric border-start border-success border-3">
                <div class="metric-title text-success fw-bold">Clientes Nuevos</div>
                <div class="metric-value text-white" id="clientesNuevos">0</div>
                <small class="text-white-50">Registrados hoy</small>
            </div>
        </div>

        <div class="card chart-card">
            <div class="mb-4">
                <h5 class="text-white mb-1">Actividad de Salidas</h5>
                <span class="text-white-50 small">Histórico de pedidos realizados</span>
            </div>
            <div class="chart-container">
                <canvas id="ventasChart"></canvas>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStockCritico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Detalle de Stock Crítico
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr class="text-muted small uppercase">
                                    <th>Producto</th>
                                    <th class="text-center">Stock Actual</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="listaStockCriticoDetalle">
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-white-50">Cargando datos...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function copiarNombre(texto, btn) {
            navigator.clipboard.writeText(texto).then(() => {
                const icon = btn.querySelector('i');
                icon.classList.replace('bi-clipboard', 'bi-check-lg');
                icon.classList.add('text-success');

                setTimeout(() => {
                    icon.classList.replace('bi-check-lg', 'bi-clipboard');
                    icon.classList.remove('text-success');
                }, 2000);
            }).catch(err => {
                console.error('Error al copiar: ', err);
            });
        }
        document.addEventListener('DOMContentLoaded', function() {

            fetch('frontend/v2/panel/metricas')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('stockCritico').innerText = data.stock_critico;
                    document.getElementById('pedidosPendientes').innerText = data.pedidos_pendientes;
                    document.getElementById('clientesNuevos').innerText = data.clientes_nuevos || 0;

                    const tbody = document.getElementById('listaStockCriticoDetalle');
                    if (data.productos_detalles && data.productos_detalles.length > 0) {
                        tbody.innerHTML = data.productos_detalles.map(p => `
                <tr>
                    <td class="fw-bold">${p.name}</td>
                    <td class="text-center"><span class="badge bg-danger">${p.stock}</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-light border-0" 
                                onclick="copiarNombre('${p.name.replace(/'/g, "\\'")}', this)" 
                                title="Copiar nombre">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
                    } else {
                        tbody.innerHTML =
                            '<tr><td colspan="3" class="text-center py-4">No hay productos bajo el mínimo.</td></tr>';
                    }

                    const list = document.getElementById('topProductosList');
                    if (data.top_productos && data.top_productos.length > 0) {
                        let html = '<div class="ranking-list">';
                        data.top_productos.slice(0, 3).forEach((prod, index) => {
                            const pos = index + 1;
                            html += `
                    <div class="ranking-item">
                        <div class="d-flex align-items-center">
                            <span class="ranking-pos">${pos}</span>
                            <span class="ranking-name text-white">${prod.name}</span>
                        </div>
                        <span class="badge bg-info text-dark">${prod.total_vendidos} vendidos</span>
                    </div>`;
                        });
                        html += '</div>';
                        list.innerHTML = html;
                    } else {
                        list.innerHTML = '<div class="text-white-50 small">Sin rotación esta semana</div>';
                    }
                });

            fetch('frontend/v2/panel/ventas-mes')
                .then(res => res.json())
                .then(data => {
                    const ctx = document.getElementById('ventasChart');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(i => i.fecha),
                            datasets: [{
                                label: 'Pedidos',
                                data: data.map(i => i.pedidos),
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59,130,246,0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#3b82f6'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#94a3b8'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        color: '#94a3b8'
                                    },
                                    grid: {
                                        color: 'rgba(255,255,255,0.05)'
                                    }
                                }
                            }
                        }
                    });
                });
        });
    </script>
@endsection
