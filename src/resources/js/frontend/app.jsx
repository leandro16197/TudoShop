import React, { useState } from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";

import Navbar from "./components/Navbar";
import ProductDetail from "./pages/ProductDetail";
import Home from "./pages/homes";

import "../../css/app.css";

export default function App() {
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [hasSearched, setHasSearched] = useState(false);
  const handleResults = (data, searched) => { setResults(data);setHasSearched(searched);};
  return (
    <BrowserRouter>
      <Navbar onResults={handleResults} onLoading={setLoading}/>

      <main className="app-content">
        <Routes>
          <Route path="/" element={<Home results={results} loading={loading} hasSearched={hasSearched} />} />
          <Route path="/home"  element={<Home results={results} loading={loading} hasSearched={hasSearched} />} />
          <Route path="/productos/:id" element={<ProductDetail />} />
        </Routes>
      </main>
    </BrowserRouter>
  );
}
