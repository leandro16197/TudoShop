import React, { useEffect, useState } from "react";
import { Link } from "react-router-dom";
export default function CategoriesSection() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
     fetch("api/frontend/v1/categorias")
      .then(res => res.json())
      .then(data => {
        setCategories(data);
      })
      .catch(() => {
        setCategories([]);
      })
      .finally(() => setLoading(false));
  }, []);
  
  if (loading) {
        return (
            <div className="loading-container">
                <div className="spinner"></div>
                <p>Cargando</p>
            </div>
        );
  }
  return (
    <div className="categories-section">
      <h2 className="categories-title">Explorar Categorías</h2>

      <div className="categories-grid">
        {categories.map(cat => (
            <Link 
              key={cat.id} 
              to={`/catalogo?categoria=${cat.id}`}
              className="category-card"
            >
              <div className="category-image-wrapper">
                <img src={cat.imagen} alt={cat.nombre} />
                <div className="category-overlay"></div>
              </div>
              <h3 className="category-name">{cat.nombre}</h3>
            </Link>
        ))}
      </div>
    </div>
  );
}
