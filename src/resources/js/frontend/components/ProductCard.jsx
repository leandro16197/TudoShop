import React from 'react';

import { Link } from 'react-router-dom';

export default function ProductCard({ product }) {
    return (
        <Link
            to={`/productos/${product.id}`}
            className="product-card"
            style={{ textDecoration: 'none', color: 'inherit' }}
        >
            <h5>{product.name}</h5>
            <p>{product.description || 'Sin descripción'}</p>

            <div className="product-footer">
                <span>${parseFloat(product.price).toFixed(2)}</span>

                {product.active ? (
                    <span className="badge badge-success">Disponible</span>
                ) : (
                    <span className="badge badge-secondary">Agotado</span>
                )}
            </div>
        </Link>
    );
}
