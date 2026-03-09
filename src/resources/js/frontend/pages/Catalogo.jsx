import React, { useEffect, useState, useRef } from "react";
import { useSearchParams } from "react-router-dom";
import Sidebar from "../components/Sidebar";
import ProductList from "../components/ProductList";

export default function Catalogo() {
  const [catalogo, setCatalogo] = useState(null);
  const topRef = useRef(null);
  const [searchParams, setSearchParams] = useSearchParams();

  const [filters, setFilters] = useState({
    categoria: searchParams.get("categoria") || "", 
    marca: searchParams.get("marca") || "",
    min_price: searchParams.get("min_price") || "",
    max_price: searchParams.get("max_price") || "",
    page: parseInt(searchParams.get("page")) || 1
  });
  useEffect(() => {
    setFilters(prev => ({
      ...prev,
      categoria: searchParams.get("categoria") || "",
      marca: searchParams.get("marca") || "",
      page: 1 
    }));
  }, [searchParams]);

  useEffect(() => {
    const delayDebounceFn = setTimeout(() => {
      const cleanFilters = Object.fromEntries(
        Object.entries(filters).filter(([_, v]) => v !== "" && v !== null)
      );

      const query = new URLSearchParams(cleanFilters).toString();
      setSearchParams(cleanFilters, { replace: true });
      fetch(`api/frontend/v1/catalogo?${query}`)
        .then(res => res.json())
        .then(data => setCatalogo(data))
        .catch(err => console.error("Error al cargar el catálogo:", err));

      if (filters.page > 1) {
        topRef.current?.scrollIntoView({ behavior: 'smooth' });
      }
    }, 500);

    return () => clearTimeout(delayDebounceFn);
  }, [filters, setSearchParams]);

  return (
    <div className="catalog-wrapper">
      <div className="catalog-header" ref={topRef}>
        <h1>Explora nuestro Catálogo</h1>
      </div>

      <div className="catalog-layout">
        <Sidebar filters={filters} setFilters={setFilters} />
        <ProductList 
          catalogo={catalogo} 
          filters={filters} 
          setFilters={setFilters} 
        />
      </div>
    </div>
  );
}