import React, { useState, useEffect } from "react";

export default function Sidebar({ filters = {}, setFilters }) {
  const [categorias, setCategorias] = useState([]);
  const [marcas, setMarcas] = useState([]);

  // Traer categorías y marcas desde el backend
  useEffect(() => {
    fetch("api/frontend/v1/categorias")
      .then(res => res.json())
      .then(data => setCategorias(data))
      .catch(err => console.error("Error al cargar categorías:", err));

    fetch("api/frontend/v1/marcas")
      .then(res => res.json())
      .then(data => setMarcas(data))
      .catch(err => console.error("Error al cargar marcas:", err));
  }, []);

  const handleChange = (e) => {
    setFilters({
      ...filters,
      [e.target.name]: e.target.value,
      page: 1 // Resetea la página al cambiar cualquier filtro
    });
  };

  return (
    <aside className="catalog-sidebar">
      <h2>Filtros</h2>

      <div className="filter-box">
        <h3>Categoría</h3>
        <select
          name="categoria"
          value={filters.categoria || ""}
          onChange={handleChange}
        >
          <option value="">Todas</option>
          {categorias.map(cat => (
            <option key={cat.id} value={cat.id}>
              {cat.nombre}
            </option>
          ))}
        </select>
      </div>

      <div className="filter-box">
        <h3>Marca</h3>
        <select
          name="marca"
          value={filters.marca || ""}
          onChange={handleChange}
        >
          <option value="">Todas</option>
          {marcas.map(m => (
            <option key={m.id} value={m.id}>
              {m.nombre}
            </option>
          ))}
        </select>
      </div>

    <div className="filter-box">
      <h3>Precio Máximo: ${filters.max_price || "10000"}</h3>

      <input type="range" name="max_price" min="0"  max="50000" step="100"  value={filters.max_price || "0"} onChange={handleChange}  className="price-slider" />

      <div className="price-labels">
        <span>$0</span>
        <span>$50.000+</span>
      </div>
    </div>
    </aside>
  );
}