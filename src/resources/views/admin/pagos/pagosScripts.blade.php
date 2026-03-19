<script>
    // Variable global para poder destruir la gráfica anterior antes de crear la nueva
    let chartInstance = null;

    document.addEventListener('DOMContentLoaded', function() {

        function cargarDashboard(inicio = '', fin = '') {
            const params = new URLSearchParams();
            if (inicio) params.append('inicio', inicio);
            if (fin) params.append('fin', fin);

            fetch(`{{ route('admin.finanzas.cards') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const moneda = (valor) =>
                        `$ ${parseFloat(valor).toLocaleString('es-AR', {minimumFractionDigits: 2})}`;

                    document.getElementById('card-mes-actual').innerText = moneda(data.comparativa
                        .mes_actual);
                    document.getElementById('card-mes-pasado').innerText = moneda(data.comparativa
                        .mes_pasado);
                    document.getElementById('card-completados').innerText = data.cards.completados;
                    document.getElementById('card-rechazados').innerText = data.cards.rechazados;
                    document.getElementById('card-pendientes').innerText = data.cards.pendientes;
                })
                .catch(error => console.error('Error en cards:', error));

            fetch(`{{ route('admin.finanzas.grafica') }}?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('canvasFinanzas').getContext('2d');
                    if (chartInstance) {
                        chartInstance.destroy();
                    }

                    if (!data || data.length === 0) return;

                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(0, 255, 127, 0.4)');
                    gradient.addColorStop(1, 'rgba(0, 255, 127, 0)');

                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.fecha),
                            datasets: [{
                                label: 'Ventas $',
                                data: data.map(item => item.total),
                                borderColor: '#00ff7f',
                                backgroundColor: gradient,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 3,
                                pointBackgroundColor: '#00ff7f',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => 'Ventas: $' + context.parsed.y
                                            .toLocaleString('es-AR')
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(255, 255, 255, 0.05)'
                                    },
                                    ticks: {
                                        color: '#888',
                                        callback: value => '$' + value.toLocaleString('es-AR')
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#888'
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error en gráfica:', error));
        }

        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const inicio = document.getElementById('fecha_inicio').value;
                const fin = document.getElementById('fecha_fin').value;
                cargarDashboard(inicio, fin);
            });
        }

        cargarDashboard(); 
    });
</script>
