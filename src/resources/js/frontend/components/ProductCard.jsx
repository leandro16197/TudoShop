import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useCart } from '../context/CartContext';
import 'bootstrap-icons/font/bootstrap-icons.css';

export default function ProductCard({ product, loading, isCarousel = false }) {

  const { addToCart } = useCart();
  const [quantity, setQuantity] = useState(1);

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

  const stock = product.stock ?? 0;

  const handleQuantityChange = (value) => {
    let newValue = parseInt(value) || 1;

    if (newValue < 1) newValue = 1;
    if (newValue > stock) newValue = stock;

    setQuantity(newValue);
  };

  const handleAddToCart = (e) => {
    e.preventDefault();
    e.stopPropagation();

    if (stock === 0) return;

    addToCart(product, quantity);
  };

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

            <p className={`stock-dinamico 
              ${product.stock === 0 ? "sin-stock" : 
                product.stock <= 10 ? "poco-stock" : "en-stock"}`}>
              
              {product.stock === 0 && "Agotado"}
              {product.stock > 0 && product.stock <= 10 && `¡Últimas ${product.stock} unidades!`}
              {product.stock > 10 && "Disponible en stock"}
            </p>
          </div>


          {stock > 0 && (
            <div className="fila-carrito">
              <div className="selector-cantidad">
                <button
                  type="button"
                  onClick={(e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (quantity > 1) setQuantity(quantity - 1);
                  }}
                >
                  -
                </button>

                <input
                  type="number"
                  min="1"
                  max={stock}
                  value={quantity}
                  onClick={(e) => {
                    e.preventDefault();
                    e.stopPropagation();
                  }}
                  onChange={(e) => handleQuantityChange(e.target.value)}
                />

                <button
                  type="button"
                  onClick={(e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    if (quantity < stock) setQuantity(quantity + 1);
                  }}
                >
                  +
                </button>
              </div>

              <button
                type="button"
                onClick={handleAddToCart}
                className="btn-icono-carrito"
              >
                <i className="bi bi-cart-plus"></i>
              </button>
            </div>
          )}

          <button
            type="button"
            className="details-btn"
            onClick={(e) => e.stopPropagation()}
          >
            Ver detalles
          </button>

        </div>

      </Link>
    </div>
  );
}