import React, { useState, useEffect } from 'react';
import DetallePedidoModal from './DetallePedidoModal'; 

export default function MisPedidos() {
    const [paginacion, setPaginacion] = useState({
        data: [],
        current_page: 1,
        last_page: 1
    });
    const [loading, setLoading] = useState(true);
    const [pedidoSeleccionado, setPedidoSeleccionado] = useState(null);

    const fetchPedidos = async (page = 1) => {
        setLoading(true);
        const token = sessionStorage.getItem("token");
        try {
            const response = await fetch(`api/frontend/v1/mis-pedidos?page=${page}`, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (response.ok) {
                setPaginacion(data);
            }
        } catch (error) {
            console.error("Error al obtener pedidos:", error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchPedidos();
    }, []);

    if (loading) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>Cargando tus pedidos...</p>
            </div>
        );
    }

    return (
        <div className="pedidos-view">
            <header className="pedidos-header">
                <h2>Mis Pedidos</h2>
                <div className="header-line"></div>
                <p className="subtitle">Consulta el historial y estado de tus compras</p>
            </header>
            
            <div className="pedidos-grid">
                {paginacion.data.length > 0 ? (
                    paginacion.data.map((pedido) => (
                        <div key={pedido.pedido_id} className="pedido-card-item">
                            <div className="pedido-card-header">
                                <h3>#{pedido.pedido_id}</h3>
                                <span className={`status-badge ${pedido.estado.toLowerCase()}`}>
                                    {pedido.estado}
                                </span>
                            </div>

                            <div className="pedido-card-info">
                                <div className="info-row">
                                    <span className="info-label">Fecha</span>
                                    <span className="info-value">{pedido.fecha_formateada}</span>
                                </div>
                                <div className="info-row">
                                    <span className="info-label">Total</span>
                                    <span className="info-value total">
                                        ${Number(pedido.total).toLocaleString('es-AR')}
                                    </span>
                                </div>
                            </div>
                            <button  className="btn-detalle-pedido"  onClick={() => setPedidoSeleccionado(pedido)}>
                                Ver {pedido.productos.length} Productos
                            </button>
                        </div>
                    ))
                ) : (
                    <p className="no-pedidos">Aún no has realizado ninguna compra.</p>
                )}
            </div>

            {pedidoSeleccionado && ( <DetallePedidoModal  pedido={pedidoSeleccionado} onClose={() => setPedidoSeleccionado(null)} />)}
            {paginacion.last_page > 1 && (
                <div className="pagination-container">
                    <button className="btn-pagination" disabled={paginacion.current_page === 1}onClick={() => fetchPedidos(paginacion.current_page - 1)}>
                        Anterior
                    </button>
                    
                    <span className="page-info">
                        Página <strong>{paginacion.current_page}</strong> de {paginacion.last_page}
                    </span>

                    <button className="btn-pagination" disabled={paginacion.current_page === paginacion.last_page}onClick={() => fetchPedidos(paginacion.current_page + 1)}>
                        Siguiente
                    </button>
                </div>
            )}
        </div>
    );
}