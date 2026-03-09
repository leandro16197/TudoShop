import React from 'react';
import { Link, useSearchParams } from 'react-router-dom';

const ErrorPage = () => {
    const [searchParams] = useSearchParams();
    const pedidoId = searchParams.get('external_reference');

    return (
        <div className="success-container">
            <div className="success-card">
                <div className="success-header" style={{ backgroundColor: '#dc2626' }}>
                    {/* Icono de error */}
                    <div className="success-icon" style={{ backgroundColor: '#fee2e2', color: '#ef4444' }}>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h2>Hubo un problema</h2>
                    <p>No pudimos procesar tu pago. Por favor, intenta nuevamente.</p>
                </div>

                <div className="success-body">
                    <div className="success-info">
                        <h4>Detalle de la operación</h4>
                        {pedidoId && (
                            <div className="info-row">
                                <span>Orden</span>
                                <b>#{pedidoId}</b>
                            </div>
                        )}
                        <div className="info-row">
                            <span>Estado</span>
                            <span className="status" style={{ color: '#ef4444' }}>Rechazado</span>
                        </div>
                    </div>

                    <div className="success-buttons">
                        <Link to="/checkout" className="btn-primary" style={{ backgroundColor: '#dc2626' }}>
                            Reintentar pago
                        </Link>
                        <Link to="/" className="btn-secondary">
                            Volver al inicio
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ErrorPage;