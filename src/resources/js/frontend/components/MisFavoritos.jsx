import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

export default function MisFavoritos() {
    const [favoritos, setFavoritos] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchFavoritos = async () => {
            const token = sessionStorage.getItem("token");
            try {
                const response = await fetch('/api/frontend/v1/mis-favoritos', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) {
                    const data = await response.json();
                    setFavoritos(data);
                }
            } catch (error) {
                console.error("Error favoritos:", error);
            } finally {
                setLoading(false);
            }
        };
        fetchFavoritos();
    }, []);

    if (loading) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>Cargando tus favoritos...</p>
            </div>
        );
    }

    return (
        <div className="pedidos-view">
            <header className="pedidos-header">
                <h2>Mis Favoritos</h2>
                <div className="header-line"></div>
                <p className="subtitle">Productos que guardaste en favoritos</p>
            </header>

            <div className="pedidos-grid">
                {favoritos.length > 0 ? (
                    favoritos.map(prod => (
                        <div key={prod.id} className="pedido-card-item fav-card">
                            <div className="pedido-card-header" style={{ borderBottom: 'none' }}>
                                <img 
                                    src={prod.image} 
                                    alt={prod.name} 
                                    className="fav-product-img"
                                    style={{ width: '100%', height: '150px', objectFit: 'contain', borderRadius: '8px' }}
                                />
                            </div>

                            <div className="pedido-card-info">
                                <h3 style={{ fontSize: '1.1rem', marginBottom: '5px' }}>{prod.name}</h3>
                                
                                <div className="info-row">
                                    <span className="info-label">Marca</span>
                                    <span className="info-value">{prod.marca}</span>
                                </div>
                                
                                <div className="info-row">
                                    <span className="info-label">Precio</span>
                                    <span className="info-value total">
                                        ${Number(prod.price).toLocaleString('es-AR')}
                                    </span>
                                </div>
                            </div>
                            <Link to={`/producto/${prod.id}`} className="btn-detalle-pedido" style={{ textAlign: 'center', textDecoration: 'none', display: 'block' }}>
                                Ver Producto
                            </Link>
                        </div>
                    ))
                ) : (
                    <p className="no-pedidos">Aún no tienes productos guardados como favoritos.</p>
                )}
            </div>
        </div>
    );
}