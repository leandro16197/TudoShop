import React, { useEffect, useState } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import axios from 'axios';

const SuccessPage = () => {

    const [searchParams] = useSearchParams();
    const [loading, setLoading] = useState(true);

    const paymentId = searchParams.get('payment_id');
    const status = searchParams.get('status');
    const pedidoId = searchParams.get('external_reference');

    useEffect(() => {

        const confirmarPagoEnServidor = async () => {

            if (status === 'approved' && pedidoId) {

                try {

                    await axios.post('/api/frontend/v1/pedidos/confirmar-pago', {
                        pedido_id: pedidoId,
                        payment_id: paymentId,
                        status: status
                    });

                } catch (error) {

                    console.error("Error al actualizar el pedido:", error);

                } finally {

                    setTimeout(() => setLoading(false), 1000);

                }

            } else {

                setLoading(false);

            }

        };

        confirmarPagoEnServidor();

    }, [pedidoId, status, paymentId]);


    return (

        <div className="success-container">

            <div className="success-card">

                <div className="success-header">

                    {loading ? (

                        <div className="loader"></div>

                    ) : (

                        <div className="success-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="3"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    d="M5 13l4 4L19 7"
                                />

                            </svg>

                        </div>

                    )}

                    {!loading && (
                        <>
                            <h2>¡Pago aprobado!</h2>
                            <p>Tu compra fue realizada con éxito</p>
                        </>
                    )}

                </div>

                {!loading && (

                    <div className="success-body">

                        <div className="success-info">

                            <h4>Detalle de la operación</h4>

                            <div className="info-row">
                                <span>Orden</span>
                                <b>#{pedidoId}</b>
                            </div>

                            <div className="info-row">
                                <span>Transacción</span>
                                <span>{paymentId}</span>
                            </div>

                            <div className="info-row">
                                <span>Estado</span>
                                <span className="status">Acreditado</span>
                            </div>

                        </div>

                        <div className="success-buttons">

                            <Link to="/perfil" className="btn-primary">
                                Ver mis pedidos
                            </Link>

                            <Link to="/catalogo" className="btn-secondary">
                                Seguir comprando
                            </Link>

                        </div>

                    </div>

                )}

            </div>

        </div>

    );

};

export default SuccessPage;