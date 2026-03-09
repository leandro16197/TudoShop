import React, { useState, useEffect } from "react";

export default function Sidebar({ filters = {}, setFilters }) {
  const [categorias, setCategorias] = useState([]);
  const [marcas, setMarcas] = useState([]);

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
      page: 1 
    });
  };

  // Función para eliminar un filtro específico
  const removeFilter = (key) => {
    setFilters({
      ...filters,
      [key]: "", // Reseteamos el valor
      page: 1
    });
  };

  // Buscamos el nombre de la categoría o marca actual para mostrarlo en el chip
  const nombreCategoriaActiva = categorias.find(c => String(c.id) === String(filters.categoria))?.nombre;
  const nombreMarcaActiva = marcas.find(m => String(m.id) === String(filters.marca))?.nombre;

  return (
    <aside className="catalog-sidebar">
      <h2>Filtros</h2>

      {/* --- SECCIÓN DE FILTROS ACTIVOS --- */}
      <div className="active-filters">
        {filters.categoria && (
          <div className="filter-chip" onClick={() => removeFilter("categoria")}>
             <span>✕ {nombreCategoriaActiva || "Categoría"}</span>
          </div>
        )}
        
        {filters.marca && (
          <div className="filter-chip" onClick={() => removeFilter("marca")}>
             <span>✕ {nombreMarcaActiva || "Marca"}</span>
          </div>
        )}

        {filters.max_price > 0 && filters.max_price < 10000 && (
          <div className="filter-chip" onClick={() => removeFilter("max_price")}>
             <span>✕ Hasta ${filters.max_price}</span>
          </div>
        )}
      </div>
      
      <hr />

      <div className="filter-box">
        <h3>Categoría</h3>
        <select name="categoria" value={filters.categoria || ""} onChange={handleChange}>
          <option value="">Todas</option>
          {categorias.map(cat => (
            <option key={cat.id} value={cat.id}>{cat.nombre}</option>
          ))}
        </select>
      </div>

      <div className="filter-box">
        <h3>Marca</h3>
        <select name="marca" value={filters.marca || ""} onChange={handleChange}>
          <option value="">Todas</option>
          {marcas.map(m => (
            <option key={m.id} value={m.id}>{m.nombre}</option>
          ))}
        </select>
      </div>

      <div className="filter-box">
        <h3>Precio Máximo: ${filters.max_price || "10000"}</h3>
        <input 
          type="range" 
          name="max_price" 
          min="0" 
          max="10000" 
          step="1000" 
          value={filters.max_price || "0"} 
          onChange={handleChange} 
          className="price-slider" 
        />
      </div>
    </aside>
  );
}