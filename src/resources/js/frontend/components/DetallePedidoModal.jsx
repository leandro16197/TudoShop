import React from 'react';
export default function DetallePedidoModal({ pedido, onClose }) {
    if (!pedido) return null;

    return (
        <div className="modal-overlay" onClick={onClose}>
            <div className="modal-card" onClick={e => e.stopPropagation()}>
                <button className="close-modal" onClick={onClose}>&times;</button>
                
               <div className="modal-header-container">
                    <h3 className="modal-title">
                        Detalle del Pedido <span style={{ color: '#e67e22' }}>#{pedido.pedido_id}</span>
                    </h3>
                    <button className="close-modal" onClick={onClose}>&times;</button>
                </div>
                
                <div className="modal-body">
                    <table className="tabla-productos">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            {pedido.productos.map((prod) => (
                                <tr key={prod.id}>
                                    <td className="col-prod" style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                                        <img src={prod.imagen} alt={prod.nombre} style={{ width: '45px', borderRadius: '8px' }} />
                                        <span>{prod.nombre}</span>
                                    </td>
                                    <td>{prod.cantidad}</td>
                                    <td>${Number(prod.precio).toLocaleString()}</td>
                                    <td><strong>${Number(prod.total_linea).toLocaleString()}</strong></td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>

                <div className="modal-footer" style={{ marginTop: '20px', textAlign: 'right', borderTop: '1px solid #eee', paddingTop: '15px' }}>
                    <p style={{ fontSize: '1.1rem' }}>Total: <strong>${Number(pedido.total).toLocaleString()}</strong></p>
                </div>
            </div>
        </div>
    );
}