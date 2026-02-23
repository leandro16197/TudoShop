import React, { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";
import FeaturedProductCard from "./ProductCardCarrousel";

export default function FeaturedProductsCarousel() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const scrollRef = useRef(null);
  const navigate = useNavigate();

  useEffect(() => {
    setLoading(true); 
    fetch("api/frontend/v1/destacados")
      .then(res => res.json())
      .then(data => {
        setProducts(data);
        setLoading(false); 
      })
      .catch(() => {
        setProducts([]);
        setLoading(false);
      });
  }, []);

  const handleScroll = (direction) => {
    const container = scrollRef.current;
    if (!container) return;
    const scrollAmount = container.clientWidth * 0.8;

    if (direction === "right") {
      if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 1) {
        container.scrollTo({ left: 0, behavior: "smooth" });
      } else {
        container.scrollBy({ left: scrollAmount, behavior: "smooth" });
      }
    } else {
      if (container.scrollLeft <= 0) {
        container.scrollTo({ left: container.scrollWidth, behavior: "smooth" });
      } else {
        container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
      }
    }
  };

  if (loading) {
    return (
      <div className="loading-container-mini">
        <div className="spinner"></div>
        <p>Cargando productos destacados...</p>
      </div>
    );
  }

  if (products.length === 0) return null;

  return (
    <div className="featured-section">
      <div className="featured-header">
        <h2>Productos Destacados</h2>
        <button className="catalog-btn" onClick={() => navigate("/catalogo")}>
          Ver Todos
        </button>
      </div>

      <div className="carousel-wrapper">
        <button className="carousel-btn left" onClick={() => handleScroll("left")}>‹</button>

        <div className="carousel" ref={scrollRef}>
          {products.map(product => (
            <div key={product.id} className="carousel-item">
              <FeaturedProductCard product={product} />
            </div>
          ))}
        </div>

        <button className="carousel-btn right" onClick={() => handleScroll("right")}>›</button>
      </div>
    </div>
  );
}