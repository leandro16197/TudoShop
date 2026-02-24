import React from "react";
import ProductCard from "../components/ProductCard";
import HeroBanner from "../components/HeroBanner";
import CategoriasSection from "../components/CategoriasSection";
import FeaturedProductsCarousel from "../components/FeaturedProductsCarousel";

export default function Home() {
  return (
    <>
      <HeroBanner />
      <CategoriasSection />
      <FeaturedProductsCarousel />
    </>
  );
}