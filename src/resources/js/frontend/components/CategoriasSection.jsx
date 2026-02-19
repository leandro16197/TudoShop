import React, { useEffect, useState } from "react";

export default function CategoriesSection() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch("/frontend/v1/categorias")
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
    return <p style={{ textAlign: "center" }}>Cargando categorías...</p>;
  }

  return (
    <div className="categories-section">
      <h2 className="categories-title">Explorar Categorías</h2>

      <div className="categories-grid">
        {categories.map(cat => (
            <div key={cat.id} className="category-card">
              <div className="category-image-wrapper">
                <img 
                  src={`http://localhost:8000/storage/${cat.imagen}`} 
                  alt={cat.nombre} 
                />
                <div className="category-overlay"></div>
              </div>

              <h3 className="category-name">{cat.nombre}</h3>
            </div>
        ))}
      </div>
    </div>
  );
}
