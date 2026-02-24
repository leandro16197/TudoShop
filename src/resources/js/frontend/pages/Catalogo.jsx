import React, { useEffect, useState, useRef } from "react";
import Sidebar from "../components/Sidebar";
import ProductList from "../components/ProductList";

export default function Catalogo() {
  const [catalogo, setCatalogo] = useState(null);
  
  const topRef = useRef(null);

  const [filters, setFilters] = useState({
    categoria: "",
    marca: "",
    min_price: "",
    max_price: "",
    page: 1
  });

  useEffect(() => {
      const delayDebounceFn = setTimeout(() => {
          const query = new URLSearchParams(filters).toString();
          
          fetch(`api/frontend/v1/catalogo?${query}`)
              .then(res => res.json())
              .then(data => setCatalogo(data))
              .catch(err => console.error("Error:", err));

          if (filters.page > 1) {
              topRef.current?.scrollIntoView({ behavior: 'smooth' });
          }
      }, 500);
      return () => clearTimeout(delayDebounceFn);
  }, [filters]);

  return (
    <div className="catalog-wrapper">

      <div className="catalog-header" ref={topRef}>
        <h1>Explora nuestro Catálogo</h1>
      </div>

      <div className="catalog-layout">
        <Sidebar filters={filters} setFilters={setFilters} />
        <ProductList catalogo={catalogo} filters={filters} setFilters={setFilters} />
      </div>
    </div>
  );
}