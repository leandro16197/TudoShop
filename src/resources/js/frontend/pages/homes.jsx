import React from "react";
import ProductCard from "../components/ProductCard";
import HeroBanner from "../components/HeroBanner";
import CategoriasSection from "../components/CategoriasSection";
import FeaturedProductsCarousel from "../components/FeaturedProductsCarousel";


export default function Home({ results, loading, hasSearched }) {
  return (
    <>
      {loading && (
        <div className="results-wrapper">
          <div className="product-list-container">
            <p>Buscando...</p>
          </div>
        </div>
      )}

      {!loading && hasSearched && results.length === 0 && (
        <div className="results-wrapper">
          <div className="product-list-container">
            <p>No se encontraron productos</p>
          </div>
        </div>
      )}

      {!loading && results.length > 0 && (
        <div className="results-wrapper">
          <div className="product-list-container">
            {results.map(product => (
              <ProductCard
                key={product.id}
                product={product}
              />
            ))}
          </div>
        </div>
      )}

      <HeroBanner />
      <CategoriasSection />
      <FeaturedProductsCarousel />

    </>
  );
}
