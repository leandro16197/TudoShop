import React from "react";
import ProductCard from "../components/ProductCard";
import HeroBanner from "../components/HeroBanner";

export default function Home({ results }) {
  return (
    <>
      {results.length > 0 && (
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
    </>
  );
}
