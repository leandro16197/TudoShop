import React, { useEffect, useState, useRef } from "react";
import { useNavigate } from "react-router-dom";
import ProductCard from "./ProductCard";

export default function FeaturedProductsCarousel() {
  const [products, setProducts] = useState([]);
  const scrollRef = useRef(null);
  const navigate = useNavigate();

  useEffect(() => {
    fetch("/frontend/v1/destacados")
      .then(res => res.json())
      .then(data => setProducts(data))
      .catch(() => setProducts([]));
  }, []);

  const handleScroll = (direction) => {
    const container = scrollRef.current;
    if (!container) return;
    

    const scrollAmount = container.clientWidth * 0.8; 
    container.scrollBy({
      left: direction === "left" ? -scrollAmount : scrollAmount,
      behavior: "smooth"
    });
  };

  if (!products.length) return null;

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
              <ProductCard product={product} isCarousel={true} />
            </div>
          ))}
        </div>

        <button className="carousel-btn right" onClick={() => handleScroll("right")}>›</button>
      </div>
    </div>
  );
}