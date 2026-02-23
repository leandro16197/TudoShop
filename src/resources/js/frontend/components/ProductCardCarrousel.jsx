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
          <span className="featured-card__price">{formattedPrice}</span>
          <div className="featured-card__stock">
            {stock > 0 ? (
              <span className="featured-card__stock--available">
                Stock: {stock}
              </span>
            ) : (
              <span className="featured-card__stock--out">Agotado</span>
            )}
          </div>
        </div>
      </div>
    </Link>
  );
}