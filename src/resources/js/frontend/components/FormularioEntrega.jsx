import React, { useState } from 'react';
import axios from 'axios';

const FormularioEntrega = ({ onCalcularEnvio, subtotal }) => {
    const [tipoEnvio, setTipoEnvio] = useState('domicilio');
    const [cp, setCp] = useState('');

    const manejarCambioCP = async (valor) => {
        setCp(valor);
        if (valor.length >= 4) {
            try {
                const respuesta = await axios.post('/api/frontend/v1/checkout/calcular-envio', {
                    cp_cliente: valor,
                    subtotal: subtotal
                });
                onCalcularEnvio(respuesta.data.envio);
            } catch (error) {
                console.error("Error al calcular envío");
            }
        }
    };

    return (
        <div className="px-md-4">
            {/* SELECTOR DE MODO */}
            <div className="row g-3 mb-4">
                <div className="col-6">
                    <div className={`p-3 border rounded-3 text-center pointer-event ${tipoEnvio === 'domicilio' ? 'border-success bg-light' : ''}`}
                         onClick={() => setTipoEnvio('domicilio')} style={{ cursor: 'pointer' }}>
                        <div className="form-check form-check-inline m-0">
                            <input className="form-check-input" type="radio" checked={tipoEnvio === 'domicilio'} readOnly />
                            <label className="form-check-label fw-bold small">Envío a domicilio</label>
                        </div>
                    </div>
                </div>
                <div className="col-6">
                    <div className={`p-3 border rounded-3 text-center pointer-event ${tipoEnvio === 'sucursal' ? 'border-success bg-light' : ''}`}
                         onClick={() => setTipoEnvio('sucursal')} style={{ cursor: 'pointer' }}>
                        <div className="form-check form-check-inline m-0">
                            <input className="form-check-input" type="radio" checked={tipoEnvio === 'sucursal'} readOnly />
                            <label className="form-check-label fw-bold small text-muted">Retiro en sucursal</label>
                        </div>
                    </div>
                </div>
            </div>

            {/* FORMULARIO DE DIRECCIÓN */}
            <div className="row g-3">
                <div className="col-md-4">
                    <input type="text" className="form-control" placeholder="CP *" 
                           value={cp} onChange={(e) => manejarCambioCP(e.target.value)} />
                </div>
                <div className="col-md-8">
                    <input type="text" className="form-control" placeholder="Localidad *" />
                </div>
                <div className="col-md-8">
                    <input type="text" className="form-control" placeholder="Calle *" />
                </div>
                <div className="col-md-4">
                    <input type="text" className="form-control" placeholder="Número *" />
                </div>
                <div className="col-12">
                    <textarea className="form-control" rows="3" placeholder="Referencia sobre tu locación..."></textarea>
                </div>
            </div>

            <button className="btn btn-success mt-4 px-5 fw-bold py-2 rounded-3 shadow-sm">Guardar</button>
        </div>
    );
};

export default FormularioEntrega;