import React from "react";
import ProductCard from "./ProductCard";

export default function ProductList({ catalogo, filters, setFilters }) {
  return (
    <main className="catalog-main">
      <div className="catalog-grid">
        {catalogo?.data.map(prod => (
          <ProductCard key={prod.id} product={prod} />
        ))}
      </div>

      {catalogo && (
        <div className="pagination">
          <button
            disabled={!catalogo.prev_page_url}
            onClick={() =>
              setFilters(prev => ({ ...prev, page: prev.page - 1 }))
            }
          >
            ← Anterior
          </button>

          <span>
            {catalogo.current_page} / {catalogo.last_page}
          </span>

          <button
            disabled={!catalogo.next_page_url}
            onClick={() =>
              setFilters(prev => ({ ...prev, page: prev.page + 1 }))
            }
          >
            Siguiente →
          </button>
        </div>
      )}
    </main>
  );
}