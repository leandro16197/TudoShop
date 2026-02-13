import React, { useState } from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";

import Navbar from "./components/Navbar";
import ProductDetail from "./pages/ProductDetail";
import Home from "./pages/homes";

import "../../css/app.css";

export default function App() {
  const [results, setResults] = useState([]);

  return (
    <BrowserRouter>
      <Navbar onResults={setResults} />

      <main className="app-content">
        <Routes>
          <Route
            path="/"
            element={<Home results={results} />}
          />

          <Route
            path="/productos/:id"
            element={<ProductDetail />}
          />
        </Routes>
      </main>
    </BrowserRouter>
  );
}
