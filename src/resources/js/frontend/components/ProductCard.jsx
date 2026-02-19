import React from 'react';
import { Link } from 'react-router-dom';

// Agregamos isCarousel con valor por defecto false
export default function ProductCard({ product, loading, isCarousel = false }) {
    if (loading) return <div className="skeleton">Cargando...</div>;

    const cardClass = isCarousel ? "product-card is-featured" : "product-card";

    return (
        <Link to={`/productos/${product.id}`} className={cardClass}>
            {product.imagen && (
                <div className="product-image-container">
                    <img
                        src={product.imagen}
                        alt={product.nombre}
                        className="product-image"
                    />
                </div>
            )}

            <div className="product-info">
                <h5>{product.nombre}</h5>
                <p className="description">{product.descripcion || 'Sin descripción'}</p>

                <div className="product-footer">
                    <span className="price">${parseFloat(product.precio).toFixed(2)}</span>
                    <span className={`badge ${product.activo ? 'available' : 'out'}`}>
                        {product.activo ? 'Disponible' : 'Agotado'}
                    </span>
                </div>
            </div>
        </Link>
    );
}