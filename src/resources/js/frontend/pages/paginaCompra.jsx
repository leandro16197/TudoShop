import React, { useState, useEffect } from 'react';
import axios from 'axios';

const PaginaCompra = () => {
    const [cargando, setCargando] = useState(true);
    const [cart, setCart] = useState([]);
    const [tipoEnvio, setTipoEnvio] = useState('domicilio');
    const [usuario, setUsuario] = useState({ nombre: '', email: '' });
    const [montos, setMontos] = useState({subtotal: 0,costo_envio: 0,total: 0});
    const [editandoContacto, setEditandoContacto] = useState(false);
    const [pedidoId, setPedidoId] = useState(1);
    const [datosEnvio, setDatosEnvio] = useState({
        cp: '',
        localidad: '',
        direccion: '',
        nombre_destinatario: '',
        telefono: ''
    });

    const fetchCart = async () => {
        const token = sessionStorage.getItem("token");
        try {
            const res = await axios.get('/api/frontend/v1/pedidos/mi-carrito', {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.data) {
                if (res.data.productos) {
                    setCart(res.data.productos);
                    setPedidoId(res.data.pedido_id);
                }
                if (res.data.datos_envio) {
                    setDatosEnvio({
                        cp: res.data.datos_envio.cp || '',
                        localidad: res.data.datos_envio.localidad || '',
                        direccion: res.data.datos_envio.direccion || '',
                        nombre_destinatario: res.data.datos_envio.nombre_destinatario || '',
                        telefono: res.data.datos_envio.telefono || ''
                    });
                }
                setMontos({
                    subtotal: parseFloat(res.data.subtotal) || 0,
                    costo_envio: parseFloat(res.data.costo_envio) || 0, 
                    total: parseFloat(res.data.total) || 0
                });
            }
        } catch (error) {
            console.error("Error al obtener carrito", error);
        }
    };

    useEffect(() => {
        const cargarDatosIniciales = async () => {
            const token = sessionStorage.getItem("token");
            if (!token) { setCargando(false); return; }
            try {
                const headers = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };
                const [resUser] = await Promise.all([
                    axios.get('/api/frontend/v1/perfil', { headers }),
                    fetchCart()
                ]);
                setUsuario({ nombre: resUser.data.nombre || 'Usuario', email: resUser.data.email || '' });
            } catch (error) {
                console.error("Error inicial", error);
            } finally {
                setTimeout(() => setCargando(false), 500);
            }
        };
        cargarDatosIniciales();
    }, []);

    const updateQuantity = async (productoId, nuevaCantidad) => {
        if (nuevaCantidad < 1) return;
        setCart(prev => prev.map(item => item.id === productoId ? { ...item, cantidad: nuevaCantidad } : item));
        try {
            const token = sessionStorage.getItem("token");
            const response = await fetch("/api/frontend/v1/actualizar-cantidad", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${token}`,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ producto_id: productoId, cantidad: nuevaCantidad })
            });
            await fetchCart();
        } catch (error) {
            console.error("Error al actualizar:", error);
            await fetchCart();
        }
    };

    const removeFromCart = async (productoId) => {
        const token = sessionStorage.getItem("token");
        if (!token) return;
        try {
            const response = await fetch(`/api/frontend/v1/eliminar-producto/${productoId}`, {
                method: "DELETE",
                headers: { "Authorization": `Bearer ${token}` }
            });
            if (response.ok) await fetchCart();
        } catch (error) {
            console.error("Error al eliminar:", error);
        }
    };

    const guardarEmailPedido = async () => {
        const token = sessionStorage.getItem("token");
        if (!token || !pedidoId) return;

        try {
            const response = await axios.put('/api/frontend/v1/perfil/actualizar/email',
                {
                    pedido_id: pedidoId,
                    email: usuario.email
                },
                {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                }
            );

            if (response.data.status === 'success') {
                const nuevoEmail = response.data.pedido ? response.data.pedido.email : response.data.usuario.email;

                setUsuario(prev => ({
                    ...prev,
                    email: nuevoEmail
                }));
                setEditandoContacto(false);
            }
        } catch (error) {
            console.error("Error al actualizar:", error);
        }
    };
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setDatosEnvio(prev => ({
            ...prev,
            [name]: value
        }));
    };
    const guardarDatosEnvio = async () => {
        const token = sessionStorage.getItem("token");
        if (!pedidoId) return;

        try {
            const res = await axios.post('/api/frontend/v1/pedidos/guardar-envio', {
                pedido_id: pedidoId,
                ...datosEnvio
            }, {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            if (res.data.status === 'success') {
                await fetchCart();
            }
        } catch (error) {
            console.error("Error al guardar envío", error);
        }
    };

    if (cargando) {
        return (
            <div className="loading-container">
                <style>{`
                    .loading-container {
                        height: 100vh;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        background-color: #f8f9fa;
                    }
                    .spinner {
                        width: 50px;
                        height: 50px;
                        border: 5px solid #e0e0e0;
                        border-top: 5px solid #003366; /* Azul de tu marca */
                        border-radius: 50%;
                        animation: spin 1s linear infinite;
                        margin-bottom: 15px;
                    }
                    @keyframes spin {
                        0% { transform: rotate(0deg); }
                        100% { transform: rotate(360deg); }
                    }
                    .loading-container p {
                        color: #003366;
                        font-weight: 600;
                        font-family: sans-serif;
                    }
                `}</style>
                <div className="spinner"></div>
                <p>Cargando tu Compra...</p>
            </div>
        );
    }

    return (
        <div className="bg-light min-vh-100 py-4">

            <div className="main-checkout-wrapper">
                <div className="steps-container">

                    {/* PASO 1: REVISÁ TU PEDIDO */}
                    <div className="step-panel active" style={{ border: '1px solid #003366', borderRadius: '12px', overflow: 'hidden', backgroundColor: '#fff', marginBottom: '20px' }}>
                        <div className="step-header" style={{ backgroundColor: '#f8f9fa', padding: '15px 20px', borderBottom: '1px solid #eee', display: 'flex', alignItems: 'center' }}>
                            <div className="step-number" style={{ backgroundColor: '#003366', color: 'white', borderRadius: '50%', width: '25px', height: '25px', display: 'flex', alignItems: 'center', justifyContent: 'center', marginRight: '10px', fontSize: '14px', fontWeight: 'bold' }}>1</div>
                            <h5 className="step-title" style={{ margin: 0, color: '#003366', fontWeight: 'bold', textTransform: 'uppercase', fontSize: '16px' }}>REVISÁ TU PEDIDO</h5>
                        </div>
                        <style>{`
                            .delete-icon {
                                color: #6c757d; /* Gris bootstrap */
                                cursor: pointer;
                                transition: color 0.2s, transform 0.2s;
                                margin-left: 15px; /* Espacio con el precio */
                            }
                            .delete-icon:hover {
                                color: #dc3545; /* Rojo bootstrap */
                                transform: scale(1.1); /* Efecto de crecimiento */
                            }
                        `}</style>

                        <div className="step-content" style={{ padding: '20px' }}>
                            {cart && cart.length > 0 ? (
                                cart.map((item) => (
                                    <div key={item.id} className="cart-item-row" style={{ display: 'flex', alignItems: 'center', marginBottom: '20px', paddingBottom: '20px', borderBottom: '1px solid #f0f0f0' }}>

                                        <div className="product-img-container" style={{ width: '80px', height: '80px', border: '1px solid #eee', borderRadius: '8px', padding: '5px', display: 'flex', alignItems: 'center', justifyContent: 'center', marginRight: '20px', flexShrink: 0 }}>
                                            <img src={item.imagen} alt={item.nombre} style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' }} />
                                        </div>

                                        <div className="product-details" style={{ flexGrow: 1, display: 'flex', flexDirection: 'column' }}>
                                            <h6 style={{ fontWeight: '600', color: '#333', marginBottom: '10px', fontSize: '15px', lineHeight: '1.4' }}>{item.nombre}</h6>

                                            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '20px', flexWrap: 'wrap' }}>
                                                <div className="qty-wrapper" style={{ display: 'flex', alignItems: 'center', border: '1px solid #ced4da', borderRadius: '25px', padding: '2px 15px', minWidth: '120px', justifyContent: 'space-between', backgroundColor: '#fff' }}>
                                                    <button onClick={() => updateQuantity(item.id, item.cantidad - 1)} style={{ border: 'none', background: 'none', fontSize: '20px', color: '#003366', cursor: 'pointer', outline: 'none' }}>-</button>
                                                    <span style={{ fontWeight: 'bold', fontSize: '16px', color: '#333', minWidth: '30px', textAlign: 'center' }}>{item.cantidad}</span>
                                                    <button onClick={() => updateQuantity(item.id, item.cantidad + 1)} style={{ border: 'none', background: 'none', fontSize: '20px', color: '#003366', cursor: 'pointer', outline: 'none' }}>+</button>
                                                </div>


                                                <div className="price-and-actions" style={{ display: 'flex', alignItems: 'center' }}>
                                                    <div className="price-tag" style={{ fontSize: '18px', fontWeight: '700', color: '#333' }}>
                                                        ${parseFloat(item.total_linea || (item.precio * item.cantidad)).toLocaleString('es-AR')}
                                                    </div>


                                                    <div onClick={() => removeFromCart(item.id)} className="delete-icon" title="Eliminar producto">
                                                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                            <path fillRule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))
                            ) : (
                                <div style={{ textAlign: 'center', padding: '40px 20px' }}>
                                    <p style={{ color: '#666', marginBottom: '20px' }}>Tu carrito está vacío</p>
                                    <button style={{ backgroundColor: '#FF8C00', color: 'white', border: 'none', borderRadius: '12px', padding: '12px 30px', fontWeight: 'bold', fontSize: '14px', cursor: 'pointer' }}>
                                        IR A LA TIENDA
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* PASO 2: DATOS DE CONTACTO */}
                    <div className="step-panel" style={{ border: '1px solid #e0e0e0', borderRadius: '12px', backgroundColor: '#fff', marginBottom: '20px' }}>
                        <div className="step-header" style={{ padding: '15px 20px', display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderBottom: '1px solid #f0f0f0' }}>
                            <div style={{ display: 'flex', alignItems: 'center' }}>
                                <div style={{ backgroundColor: '#003366', color: 'white', borderRadius: '50%', width: '25px', height: '25px', display: 'flex', alignItems: 'center', justifyContent: 'center', marginRight: '10px', fontSize: '14px', fontWeight: 'bold' }}>2</div>
                                <h5 style={{ margin: 0, color: '#003366', fontWeight: 'bold', fontSize: '16px' }}>DATOS DE CONTACTO</h5>
                            </div>
                            {!editandoContacto && (
                                <button onClick={() => setEditandoContacto(true)} className="btn-editar-contacto" >Editar </button>
                            )}
                        </div>

                        <div className="step-content" style={{ padding: '20px' }}>
                            {editandoContacto ? (
                                <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
                                    <label style={{ fontSize: '13px', color: '#666', fontWeight: 'bold' }}>Email para el envío *</label>
                                    <div style={{ display: 'flex', gap: '10px' }}>
                                        <input
                                            type="email"
                                            value={usuario.email}
                                            onChange={(e) => setUsuario({ ...usuario, email: e.target.value })}
                                            style={{ flexGrow: 1, padding: '10px', borderRadius: '8px', border: '1px solid #ced4da' }}
                                        />
                                        <button
                                            onClick={guardarEmailPedido}
                                            style={{ backgroundColor: '#28a745', color: 'white', border: 'none', borderRadius: '8px', padding: '0 20px', fontWeight: 'bold', cursor: 'pointer' }}
                                        >
                                            Guardar
                                        </button>
                                    </div>
                                </div>
                            ) : (
                                <span style={{ color: '#333', fontSize: '15px', fontWeight: '500' }}>{usuario.email}</span>
                            )}
                        </div>
                    </div>

                    {/* PASO 3: ENVÍO */}
                    <div className="step-panel" style={{ border: '1px solid #e0e0e0', borderRadius: '12px', backgroundColor: '#fff', marginBottom: '20px' }}>
                        <div className="step-header" style={{ padding: '15px 20px', display: 'flex', alignItems: 'center', borderBottom: '1px solid #f0f0f0' }}>
                            <div style={{ backgroundColor: '#003366', color: 'white', borderRadius: '50%', width: '25px', height: '25px', display: 'flex', alignItems: 'center', justifyContent: 'center', marginRight: '10px', fontSize: '14px', fontWeight: 'bold' }}>3</div>
                            <h5 style={{ margin: 0, color: '#003366', fontWeight: 'bold', fontSize: '16px' }}>MÉTODO DE ENTREGA</h5>
                        </div>

                        <div className="p-4">
                            <div className="row g-2">
                                <div className="col-md-4">
                                    <input name="cp" className="custom-input" placeholder="CP *" value={datosEnvio.cp} onChange={handleInputChange} />
                                </div>
                                <div className="col-md-8">
                                    <input name="localidad" className="custom-input" placeholder="Localidad *" value={datosEnvio.localidad} onChange={handleInputChange} />
                                </div>
                                <div className="col-md-12">
                                    <input  name="direccion"  className="custom-input" placeholder="Calle y Altura *" value={datosEnvio.direccion} onChange={handleInputChange} />
                                </div>
                                <div className="col-md-6">
                                    <input  name="nombre_destinatario" className="custom-input" placeholder="Nombre de quien recibe *" value={datosEnvio.nombre_destinatario} onChange={handleInputChange} />
                                </div>
                                <div className="col-md-6">
                                    <input  name="telefono" className="custom-input"  placeholder="Teléfono *" value={datosEnvio.telefono} onChange={handleInputChange} />
                                </div>
                                <div className="col-12 mt-3 text-end">
                                    <button  onClick={guardarDatosEnvio}
                                        style={{
                                            backgroundColor: '#003366',
                                            color: 'white',
                                            border: 'none',
                                            padding: '10px 25px',
                                            borderRadius: '8px',
                                            fontWeight: '600',
                                            cursor: 'pointer',
                                            fontSize: '14px'
                                        }}
                                    >
                                        Confirmar datos de envío
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* RESUMEN */}
                <div className="summary-container">
                    <div className="summary-card">
                        <div className="summary-header" style={{ backgroundColor: '#003366', color: 'white', padding: '15px', fontWeight: 'bold', textAlign: 'center', borderRadius: '12px 12px 0 0' }}>
                            RESUMEN DE COMPRA
                        </div>
                        
                        <div className="summary-body" style={{ padding: '20px', backgroundColor: 'white', borderRadius: '0 0 12px 12px', boxShadow: '0 4px 6px rgba(0,0,0,0.1)' }}>
                            
                            {/* Subtotal */}
                            <div className="d-flex justify-content-between mb-2">
                                <span style={{ color: '#555' }}>Subtotal:</span>
                                <span className="fw-bold"> 
                                    ${Number(montos?.subtotal || 0).toLocaleString('es-AR')}
                                </span>
                            </div>

                            {/* Envío */}
                            <div className="d-flex justify-content-between mb-2">
                                <span style={{ color: '#555' }}>Envío:</span>
                                {(!montos.costo_envio || montos.costo_envio === 0) ? (
                                    <span className="text-success fw-bold">¡Gratis!</span>
                                ) : (
                                    <span className="fw-bold"> 
                                        ${Number(montos.costo_envio).toLocaleString('es-AR')}
                                    </span>
                                )}
                            </div>

                            <hr className="my-3" style={{ borderStyle: 'dashed', borderColor: '#ccc' }} />

                            <div className="d-flex justify-content-between align-items-center mb-4">
                                <span className="fw-bold" style={{ color: '#003366' }}>TOTAL:</span>
                                <span className="total-amount" style={{ fontSize: '24px', fontWeight: 'bold', color: '#FF8C00' }}> 
                                    ${Number(montos?.total || 0).toLocaleString('es-AR')}
                                </span>
                            </div>

                            <button 
                                className="btn-checkout"
                                disabled={ !datosEnvio.cp ||  !datosEnvio.direccion ||  !datosEnvio.localidad ||   !datosEnvio.nombre_destinatario || !pedidoId
                                }
                                style={{ width: '100%', padding: '15px', borderRadius: '10px', border: 'none', fontWeight: 'bold', fontSize: '16px', textTransform: 'uppercase', transition: 'all 0.3s ease',
                                    backgroundColor: (!datosEnvio.cp || !datosEnvio.direccion) ? '#ced4da' : '#FF8C00',
                                    color: 'white',
                                    cursor: (!datosEnvio.cp || !datosEnvio.direccion) ? 'not-allowed' : 'pointer',
                                    boxShadow: (!datosEnvio.cp || !datosEnvio.direccion) ? 'none' : '0 4px 15px rgba(255, 140, 0, 0.3)'
                                }}
                                onClick={() => {
                                    console.log("Redirigiendo a pago...");
                                }}
                            >
                                {(!datosEnvio.cp || !datosEnvio.direccion) ? 'Faltan datos de envío' : 'IR AL PAGO'}
                            </button>
                            {(!datosEnvio.cp || !datosEnvio.direccion) && (
                                <div style={{ marginTop: '12px', fontSize: '12px', color: '#dc3545', textAlign: 'center', fontWeight: '500' }}>
                                    <i className="bi bi-info-circle me-1"></i>
                                    Debe confirmar su domicilio para habilitar el pago.
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default PaginaCompra;