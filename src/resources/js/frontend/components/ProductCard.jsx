import React from 'react';
import { Link } from 'react-router-dom';

export default function ProductCard({ product, loading, isCarousel = false }) {

  if (loading) {
    return (
      <div className="product-card skeleton-card">
        <div className="skeleton-image"></div>
        <div className="skeleton-content">
          <div className="skeleton-line short"></div>
          <div className="skeleton-line"></div>
          <div className="skeleton-line medium"></div>
          <div className="skeleton-button"></div>
        </div>
      </div>
    );
  }

  if (!product) {
    return (
      <div className="product-card empty-card">
        <p>No se encontraron productos</p>
      </div>
    );
  }

  const cardClass = isCarousel
    ? "product-card product-card-featured"
    : "product-card";

  const formattedPrice = new Intl.NumberFormat("es-AR", {
    style: "currency",
    currency: "ARS",
  }).format(product.precio);

  return (
    <div className={cardClass}>
      <Link to={`/productos/${product.id}`} className="product-card-link">

        <div className="product-image-container">
          {product.imagen ? (
            <img
              src={product.imagen}
              alt={product.nombre}
              className="product-image"
            />
          ) : (
            <div className="no-image">Sin imagen</div>
          )}
        </div>

        <div className="product-info">
          <h5 className="product-title">{product.nombre}</h5>

          <p className="product-description">
            {product.descripcion || "Sin descripción"}
          </p>

          <div className="product-price-stock">
            <span className="price">{formattedPrice}</span>

            <span
              className={`stock-badge ${
                product.activo ? "available" : "out"
              }`}
            >
              {product.activo ? "Disponible" : "Agotado"}
            </span>
          </div>

          <button className="details-btn">
            Ver detalles
          </button>
        </div>

      </Link>
    </div>
  );
}