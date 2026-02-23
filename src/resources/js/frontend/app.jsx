import React, { useState } from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";

import Navbar from "./components/Navbar";
import ProductDetail from "./pages/ProductDetail";
import Home from "./pages/homes";
import Catalogo from "./pages/Catalogo";
import Footer from "./components/Footer";
import Login from "./pages/Login";
import Register from "./pages/Registro";
import Perfil from "./pages/Perfil";
import { AuthProvider } from './context/AuthContext';


import "../../css/app.css";

export default function App() {
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [hasSearched, setHasSearched] = useState(false);
  const handleResults = (data, searched) => { setResults(data);setHasSearched(searched);};
  return (
    <AuthProvider>
      <BrowserRouter>
        <Navbar onResults={handleResults} onLoading={setLoading}/>

        <main className="app-content">
          <Routes>
            <Route path="/" element={<Home results={results} loading={loading} hasSearched={hasSearched} />} />
            <Route path="/home"  element={<Home results={results} loading={loading} hasSearched={hasSearched} />} />
            <Route path="/catalogo" element={<Catalogo />} />
            <Route path="/productos/:id" element={<ProductDetail />} />
            <Route path="/login" element={<Login />} />
            <Route path="/registro" element={<Register />} />
            <Route path="/perfil" element={<Perfil />} />
          </Routes>
        </main>
        <Footer />
      </BrowserRouter>
    </AuthProvider>
  );
}
