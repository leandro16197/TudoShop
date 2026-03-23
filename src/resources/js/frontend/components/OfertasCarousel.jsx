import React, { useEffect, useState, useRef } from "react";
import FeaturedProductCard from "./ProductCardCarrousel";

export default function OfertasCarousel() {
  const [productosOferta, setProductosOferta] = useState([]);
  const [loading, setLoading] = useState(true);
  const scrollRef = useRef(null);

  useEffect(() => {
    setLoading(true);
    
    const token = sessionStorage.getItem("token");
    const headers = {
      "Accept": "application/json",
      "Content-Type": "application/json"
    };

    if (token) {
      headers["Authorization"] = `Bearer ${token}`;
    }

    fetch('/api/frontend/v1/productos/ofertas', {
      method: 'GET',
      headers: headers
    })
      .then(res => res.json())
      .then(data => {
        setProductosOferta(data);
        setLoading(false);
      })
      .catch(err => {
        console.error("Error cargando ofertas:", err);
        setLoading(false);
      });
  }, []);

  const handleScroll = (direction) => {
    const container = scrollRef.current;
    if (!container) return;
    const scrollAmount = container.clientWidth * 0.8;

    if (direction === "right") {
      container.scrollBy({ left: scrollAmount, behavior: "smooth" });
    } else {
      container.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    }
  };

  if (!loading && productosOferta.length === 0) return null;

  return (
    <div className="featured-section">
      <div className="featured-header">
        <h2>🔥 Ofertas Imperdibles</h2>
      </div>

      <div className="carousel-wrapper">
        <button className="carousel-btn left" onClick={() => handleScroll("left")}>‹</button>

        <div className="carousel" ref={scrollRef}>
          {loading 
            ? Array(5).fill(0).map((_, i) => (
                <div key={i} className="carousel-item">
                    <FeaturedProductCard product={{}} /> 
                </div>
                ))
            : productosOferta.map(product => (
                <div key={product.id} className="carousel-item">
                  <FeaturedProductCard product={product} />
                </div>
              ))
          }
        </div>

        <button className="carousel-btn right" onClick={() => handleScroll("right")}>›</button>
      </div>
    </div>
  );
}