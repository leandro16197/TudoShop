import React, { useState, useEffect } from 'react';
import axios from 'axios';

const PaginaCompra = () => {
    const [cargando, setCargando] = useState(true);
    const [tipoEnvio, setTipoEnvio] = useState('domicilio');
    const [usuario, setUsuario] = useState({ nombre: '', email: '' });
    const [montos, setMontos] = useState({ subtotal: 22750, envio: 0, total: 22750 });

    useEffect(() => {
        const cargarDatosIniciales = async () => {
            const token = sessionStorage.getItem("token"); 
            if (!token) { setCargando(false); return; }
            try {
                const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };
                const [resUser, resCarrito] = await Promise.all([
                    axios.get('/api/frontend/v1/perfil', { headers }),
                    axios.get('/api/frontend/v1/pedidos/mi-carrito', { headers })
                ]);
                setUsuario({ nombre: resUser.data.nombre || 'Usuario', email: resUser.data.email || '' });
                const subtotal = parseFloat(resCarrito.data.subtotal) || 0;
                setMontos({ subtotal, envio: 0, total: subtotal });
            } catch (error) { console.error("Error", error); } finally { setCargando(false); }
        };
        cargarDatosIniciales();
    }, []);

    if (cargando) return <div className="text-center py-5"><div className="spinner-border text-primary"></div></div>;

    return (
        <div className="bg-light min-vh-100 py-4">
            <div className="main-checkout-wrapper">
                
               
                <div className="steps-container">
                    
            
                    <div className="step-panel">
                        <div className="step-header justify-content-between">
                            <div className="d-flex align-items-center">
                                <div className="step-number">1</div>
                                <h5 className="step-title">DATOS PERSONALES</h5>
                            </div>
                            <button className="btn btn-sm btn-link text-decoration-none fw-bold" style={{color:'#FF8C00'}}>Cambiar</button>
                        </div>
                        <div className="px-4 py-3">
                            <p className="mb-0 fw-bold text-secondary">{usuario.email}</p>
                        </div>
                    </div>

                    <div className="step-panel active">
                        <div className="step-header">
                            <div className="step-number">2</div>
                            <h5 className="step-title">MÉTODO DE ENTREGA</h5>
                        </div>
                        <div className="p-4">
                            <div className="row g-3">
                                <div className="col-md-4"><input className="custom-input" placeholder="Código Postal *" /></div>
                                <div className="col-md-8"><input className="custom-input" placeholder="Localidad *" /></div>
                                <div className="col-md-12"><input className="custom-input" placeholder="Calle y Altura *" /></div>
                                <div className="col-md-12"><textarea className="custom-input" rows="2" placeholder="Notas adicionales (opcional)" /></div>
                                <div className="col-md-6"><input className="custom-input" placeholder="Nombre de quien recibe *" /></div>
                                <div className="col-md-6"><input className="custom-input" placeholder="Teléfono *" /></div>
                            </div>
                            <button className="btn btn-dark mt-3 px-5 py-2 fw-bold" style={{borderRadius:'8px', backgroundColor: '#003366'}}>
                                Guardar y continuar
                            </button>
                        </div>
                    </div>

                </div>

                <div className="summary-container">
                    <div className="summary-card">
                        <div className="summary-header">
                            <h6>RESUMEN DE COMPRA</h6>
                        </div>
                        
                        <div className="summary-body">
                            <div className="summary-row">
                                <span>Subtotal</span>
                                <span className="fw-bold text-dark">${montos.subtotal.toLocaleString('es-AR')}</span>
                            </div>
                            <div className="summary-row">
                                <span>Envío</span>
                                <span className="text-success fw-bold">¡Bonificado!</span>
                            </div>
                            <div className="summary-row">
                                <span>Impuestos</span>
                                <span className="fw-bold text-dark">$0,00</span>
                            </div>
                            
                            <div className="summary-total-row">
                                <span className="total-label">TOTAL</span>
                                <span className="total-amount">${montos.total.toLocaleString('es-AR')}</span>
                            </div>

                            <button className="btn-checkout">
                                Ir al pago
                            </button>
                            
                            <div className="security-tag">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                </svg>
                                Pago 100% Seguro
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    );
};

export default PaginaCompra;