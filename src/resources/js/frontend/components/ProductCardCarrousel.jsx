import React from 'react';
import { Link } from 'react-router-dom';

export default function FeaturedProductCard({ product }) {
  const { id, nombre, precio, imagen, stock } = product;

  const formattedPrice = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
  }).format(precio);

  return (
    <Link to={`/productos/${id}`} className="featured-card">
      <div className="featured-card__image-wrapper">
        {imagen ? (
          <img
            src={imagen}
            alt={nombre}
            loading="lazy"
            className="featured-card__image"
          />
        ) : (
          <div className="featured-card__no-image">Sin imagen</div>
        )}
      </div>
      <div className="featured-card__content">
        <h4 className="featured-card__title">{nombre}</h4>
        <div className="featured-card__footer">
          <span className="featured-card__price featured-card__price--colored">
            {formattedPrice}
          </span>
          <div className="featured-card__stock featured-card__stock--colored">
            {stock > 0 ? `STOCK: ${stock}` : "AGOTADO"}
          </div>
        </div>
      </div>
    </Link>
  );
}