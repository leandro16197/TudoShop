import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';

export default function FeaturedProductCard({ product }) {
  const { id, nombre, precio, imagen, stock, is_favorite } = product;
  const [isFav, setIsFav] = useState(!!is_favorite);
  const navigate = useNavigate();

  const formattedPrice = new Intl.NumberFormat('es-AR', {
    style: 'currency',
    currency: 'ARS',
  }).format(precio);

  const handleFavoriteClick = async (e) => {
    e.preventDefault();
    e.stopPropagation();

    const token = sessionStorage.getItem("token");
    if (!token) {
      navigate('/login');
      return;
    }

    try {
      const response = await fetch(`/api/frontend/v1/favorito/${id}`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        }
      });

      if (response.ok) {
        setIsFav(!isFav);
      }
    } catch (error) {
      console.error("Error al actualizar favorito:", error);
    }
  };
  useEffect(() => {
    setIsFav(!!is_favorite);
  }, [is_favorite]);

  return (
    <Link to={`/productos/${id}`} className="featured-card">
      <div className="featured-card__image-wrapper">
        
        <button 
          className="fav-btn fav-btn--small"
          onClick={handleFavoriteClick}
          title={isFav ? "Quitar de favoritos" : "Agregar a favoritos"}
        >
          <i className={`bi ${isFav ? 'bi-star-fill' : 'bi-star'} fav-icon`}></i>
        </button>

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